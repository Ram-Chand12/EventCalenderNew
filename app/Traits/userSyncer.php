<?php

namespace App\Traits;

use GuzzleHttp\Client;
use App\Models\golf_club;
use App\Models\wordpress_reference;
use App\Models\User;
use App\Traits\SyncerLog; // Import SyncerLog
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

trait userSyncer
{
    protected $entity_type = 'user';
    public function syncUser()
    {

        $this->userInsert();
    }

    public function userInsert()
    {
        Log::info('User Sync Start' );

        $golfClubs = golf_club::where('status', 1)->get();

        foreach ($golfClubs as $club) {
        
            if ($this->checkWordPressSite($club->url)) {

                $this->syncUsersToWordPress($club);
            } else {
                SyncerLog::logMessage('info', "URL is not correct: $club->url", $this->entity_type, $club->id); // Log info message
                
            }
        }
    }

    private function checkWordPressSite($url)
    {
        try {


            $client = new Client();
            $response = $client->get($url);
            return $response->getStatusCode() == 200;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to connect to WordPress site at $url: " . $e->getMessage(), $this->entity_type); // Log error message
            return false;
        }
    }

    //sync users to wordpress
    private function syncUsersToWordPress($club)
    {
        $client = new Client();
        // $wordpressUrl = rtrim($club->url, '/') . '/wp-json/wp/v2/users';
        $wordpressUrl = rtrim($club->url, '/') . '/wp-json/wp/v2/users';

        // Retrieve all users from the local database
        $users = User::where('status', 1)
            ->where('user_type', 'wordpress user')->get();

        foreach ($users as $user) {
           
            $user_ref_data = wordpress_reference::where('ref_id', $user->id)
                ->where('entity_type', 'user')
                ->where('club_id', $club->id)
                ->first();

            
            if ($user_ref_data) {
                
               
                if ($user_ref_data->no_of_tries < 3) {
                

                        if ($this->needsUpdating($user, $user_ref_data)) {
                


                            $responseBody = $this->updateUserOnWordPress($client, $wordpressUrl, $club, $user_ref_data->wrd_id, $user);
                            // Log info message
                            if ($responseBody) {
                                $user_ref_data->update([
                                    'no_of_tries' => 1,
                                    'status' => 3,
                                    'updated_at' => now()
                                ]);
                                $user_ref_data->touch();
                            } else {
                                $user_ref_data->update([
                                    'no_of_tries' => 1,
                                    'status' => 2,
                                    'updated_at' => now()
                                ]);
                                $user_ref_data->touch();
                            }
                        } else {
                            $user_ref_data->update([
                                'no_of_tries' => DB::raw('no_of_tries + 1'),
                                'updated_at' => now()
                            ]);
                            $user_ref_data->touch();
                        }
                    
                }
            } else {
                $response = $this->checkUserExistsOnWordPress($client, $wordpressUrl, $club, $user->email, $user->id);
                if($response){ 
                        $responseId = $response[0]['id'];
                         wordpress_reference::create([
                            'wrd_id' => $responseId,
                            'club_id' => $club->id,
                            'ref_id' => $user->id,
                            'entity_type' => 'user',
                            'no_of_tries' => 1,
                            'status' => 3,
                        ])->touch();
                } else {
                    
                    $responseBody = $this->createUserOnWordPress($client, $wordpressUrl, $club, $user);

                    if ($responseBody) {
                        wordpress_reference::create([
                            'wrd_id' => $responseBody->id,
                            'club_id' => $club->id,
                            'ref_id' => $user->id,
                            'entity_type' => 'user',
                            'no_of_tries' => 1,
                            'status' => 3,
                        ])->touch();
                    }
                }
            }
        }
    }

    //check the existance of user from wordpress
    private function checkUserExistsOnWordPress($client, $wordpressUrl, $club, $email, $user_id)
    {
        
        $clubPassword = Crypt::decryptString($club->password);
        try {
            $response = $client->get($wordpressUrl . '?search=' . urlencode($email), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword)
                ]               
            ]);
    
            $responseBody = $response->getBody();
            $users = json_decode($responseBody, true);

           

            
    
            return $users;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to check if user exists on WordPress site at {$club->url} for {$club->username}: " . $e->getMessage(), $this->entity_type, $club->id, $user_id);
    
            return false;
        }
    }
    
    

    //create user on wordpress
    private function createUserOnWordPress($client, $wordpressUrl, $club, $user)
    {
        
        $old_user = array(8, 9, 10, 11, 12, 13);
        if (!in_array($user->id, $old_user)) {
          
            $decryptPassword = Crypt::decryptString($user->password);
            $clubPassword = Crypt::decryptString($club->password);
           
            $authHeader = 'Basic ' . base64_encode($club->username . ":" . $clubPassword);
         
            try {
                $response = $client->post($wordpressUrl, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => $authHeader
                    ],
                    'json' => [
                        'username' => $user->name,
                        'password' => $decryptPassword,
                        'email' => $user->email,
                        'first_name' => $user->fname,
                        'last_name' => $user->lname,
                        'roles' => 'administrator',
                    ]
                ]);

                

                $responseBody = json_decode($response->getBody());
                SyncerLog::logMessage('info', "User created successfully user email is : {$user->email} and club is {$club->url}", $this->entity_type, $club->id, $user->id, $responseBody->id);

                return $responseBody;
            } catch (ClientException $e) {
                SyncerLog::logMessage('error', "Failed to create user {$user->name} to WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents(), $this->entity_type, $club->id, $user->id); // Log error message
                return null;
            } catch (\Exception $e) {
                SyncerLog::logMessage('error', "Failed to create user {$user->name} on WordPress site at {$club->url}: " . $e->getMessage(), $this->entity_type, $club->id, $user->id); // Log error message
                return null;
            }
        }
    }


    //update user on wordpress
    private function updateUserOnWordPress($client, $wordpressUrl, $club, $wordpressUserId, $user)
    {
log::info('test private');
        $clubPassword = Crypt::decryptString($club->password);


        try {
           
            $response = $client->put($wordpressUrl . '/' . $wordpressUserId, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword)

                ],
                'json' => [                  
                    'email' => $user->email,
                    'first_name' => $user->fname,
                    'last_name' => $user->lname,
                    'roles' => 'administrator',
                ]
            ]);

            $responseBody = json_decode($response->getBody());
            
            SyncerLog::logMessage('info', "User Successfull updated user email is : " . $user->email . " and club is " . $club->url, $this->entity_type, $club->id, $user->id, $responseBody->id); // Log info message

            return $responseBody;
        } catch (ClientException $e) {
            SyncerLog::logMessage('error', "Failed to update user {$user->name} on WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents(), $this->entity_type, $club->id, $user->id); // Log error message
            return null;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to update user {$user->name} on WordPress site at {$club->url}: " . $e->getMessage(), $this->entity_type, $club->id, $user->id); // Log error message
            return null;
        }
    }

    private function needsUpdating($user, $user_ref_data)
    {
        if ($user->updated_at > $user_ref_data->updated_at) {
            

            return true;
        } else {
           

            return false;
        }
    }
}
