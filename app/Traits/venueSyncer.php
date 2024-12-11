<?php

namespace App\Traits;

use GuzzleHttp\Client;
use App\Models\golf_club; // Corrected the model name
use App\Models\wordpress_reference; // Corrected the model name
use App\Models\Vanue; // Corrected the model name
use App\Traits\SyncerLog; // Import SyncerLog
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Crypt;

trait VenueSyncer
{
    protected $venue_entity_type = 'venue';

    public function syncVenue()
    {
        $this->venueInsert();
        $this->deleteOrphanedVenues();

    }

    public function venueInsert()
    {
      
        Log::info('Venue Sync Start' );

        $golfClubs = golf_club::where('status', 1)->get();

        foreach ($golfClubs as $club) {
            
            if ($this->checkVenueWebsite($club->url)) {
                
                $this->syncVenueToWordPress($club);
            } else {
                SyncerLog::logMessage('info', "URL is not correct: $club->url", $this->venue_entity_type, $club->id); // Log info message
                
            }
        }
    }

    private function checkVenueWebsite($url)
    {
        try {
            $client = new Client();
            $response = $client->get($url);
            return $response->getStatusCode() == 200;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to connect to WordPress site at $url: " . $e->getMessage(), $this->venue_entity_type);
            return false;
        }
    }

    //sync venue on wordpress
    private function syncVenueToWordPress($club)
    {
        $client = new Client();
        $wordpressUrl = rtrim($club->url, '/') . '/wp-json/tribe/events/v1/venues';
        

        // Retrieve all venues from the local database
        $venues = Vanue::where('status', 1)->get();

        foreach ($venues as $venue) {
            $venueRefData = wordpress_reference::where('ref_id', $venue->id)
                ->where('entity_type', $this->venue_entity_type)
                ->where('club_id', $club->id)
                ->first();
              
            if ($venueRefData && $venueRefData != '') {
                
                if ($venueRefData->no_of_tries < 3) {
                 
                    if ($this->checkVenueExistsOnWordPress($club, $venue)) {
              
                        if ($this->needsVenueUpdating($venue, $venueRefData)) {
                     
                            $responseBody = $this->updateVenueOnWordPress($client, $wordpressUrl, $club, $venueRefData->wrd_id, $venue);
                            $this->updateVenueReference($venueRefData, $responseBody);
                        } else {
                          
                            $venueRefData->increment('no_of_tries');
                            $venueRefData->touch();
                        }
                    } else {
                      
                        $responseBody = $this->createVenueOnWordPress($client, $wordpressUrl, $club, $venue);
                  
                        $this->updateVenueReference($venueRefData, $responseBody);
                    }
                }
            } else {
                if ($this->checkVenueExistsOnWordPress($club, $venue)) {

                } else {

                    $responseBody = $this->createVenueOnWordPress($client, $wordpressUrl, $club, $venue);
                    if ($responseBody) {
                        wordpress_reference::create([
                            'wrd_id' => $responseBody->id,
                            'club_id' => $club->id,
                            'ref_id' => $venue->id,
                            'entity_type' => $this->venue_entity_type,
                            'no_of_tries' => 1,
                            'status' => 3,
                        ])->touch();
                    }
                }
            }
        }
    }

    //check the existance of venue
    private function checkVenueExistsOnWordPress($club, $venue)
    {
        try {
            $venues = wordpress_reference::where('ref_id', $venue->id)
                ->where('club_id', $club->id)
                ->where('entity_type', $this->venue_entity_type)
                ->first();

            return $venues ? true : false;

        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to check if venue exists on WordPress site at {$club->url}: " . $e->getMessage(), $this->venue_entity_type, $club->id, $venue->id);
            return false;
        }
    }

    //create venue to sync on wordpress
    private function createVenueOnWordPress($client, $wordpressUrl, $club, $venue)
    {
        if($venue->id>41){
        $clubPassword = Crypt::decryptString($club->password);
        $postal_code_string = (string) $venue->postal_code;
        

        try {
            $response = $client->post($wordpressUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword),
                ],
                'json' => [
                    'venue' => $venue->name,
                    'description' => $venue->vanue_description ?? " ",
                    'address' => $venue->address,
                    'city' => $venue->city,
                    'zip' => $postal_code_string,        
                    'phone' => $venue->phone,
                    'country' => 'United States',
                    'state' => $venue->state,
                    'website' => $venue->website,
                ],
            ]);

            $responseBody = json_decode($response->getBody());
            SyncerLog::logMessage('info', "Venue created successfully: {$venue->name} for club: {$club->url}", $this->venue_entity_type, $club->id, $venue->id, $responseBody->id);

            return $responseBody;
        } catch (ClientException $e) {
            Log::info("Failed to create venue {$venue->name} to WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents());
            SyncerLog::logMessage('error', "Failed to create venue {$venue->name} to WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents(), $this->venue_entity_type, $club->id, $venue->id);
            return null;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to create venue {$venue->name} on WordPress site at {$club->url}: " . $e->getMessage(), $this->venue_entity_type, $club->id, $venue->id);
            return null;
        }
    }
    }

    //update venue on wordpress
    private function updateVenueOnWordPress($client, $wordpressUrl, $club, $wordpressVenueId, $venue)
    {
        $clubPassword = Crypt::decryptString($club->password);
        $postal_code_string = (string) $venue->postal_code;
        try {
            $response = $client->put($wordpressUrl . '/' . $wordpressVenueId, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword),
                ],
                'json' => [
                    'venue' => $venue->name,
                    'description' => $venue->vanue_description ?? " ",
                    'address' => $venue->address,
                    'city' => $venue->city,
                    'zip' => $postal_code_string,
                    'phone' => $venue->phone,
                    'country' => $venue->country,
                    'state' => $venue->state,
                    'website' => $venue->website,
                ],
            ]);

            $responseBody = json_decode($response->getBody());
            SyncerLog::logMessage('info', "Venue updated successfully: {$venue->name} for club: {$club->url}", $this->venue_entity_type, $club->id, $venue->id, $responseBody->id);

            return $responseBody;
        } catch (ClientException $e) {
            SyncerLog::logMessage('error', "Failed to update venue {$venue->name} on WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents(), $this->venue_entity_type, $club->id, $venue->id);
            return null;

        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to update venue {$venue->name} on WordPress site at {$club->url}: " . $e->getMessage(), $this->venue_entity_type, $club->id, $venue->id);
            return null;
        }
    }

    private function needsVenueUpdating($venue, $venueRefData)
    {
        return $venue->updated_at > $venueRefData->updated_at;
    }

    private function updateVenueReference($venueRefData, $responseBody)
    {
        if ($responseBody) {
            $venueRefData->update([
                'no_of_tries' => 1,
                'status' => 3,
                'updated_at' => now()
            ]);
            $venueRefData->touch();
        } else {
            $venueRefData->increment('no_of_tries');
            $venueRefData->touch();
        }
    }


    //delete venue
    private function deleteOrphanedVenues()
    {


        // Retrieve all venue references
        $venueReferences = wordpress_reference::where('entity_type', $this->venue_entity_type)->get();

        foreach ($venueReferences as $venueReference) {
            

            // Check if the venue exists in the local database
            $venueExists = Vanue::where('status', 1)->where('id', $venueReference->ref_id)->first();

            if (!$venueExists) {
                $club = golf_club::where('status', 1)->find($venueReference->club_id);
                if ($club) {
                    
                    $this->deleteVenueFromWordPress($club, $venueReference);
                } 
            }
        }

        Log::info('Completed deletion of orphaned venues.');
    }


    private function deleteVenueFromWordPress($club, $venueReference)
    {
        if($venueReference->ref_id>41){
        $client = new Client();
        $wordpressUrl = rtrim($club->url, '/') . '/wp-json/tribe/events/v1/venues/' . $venueReference->wrd_id;
        $clubPassword = Crypt::decryptString($club->password);
        try {
            $response = $client->delete($wordpressUrl, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword)
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                SyncerLog::logMessage('info', "Venue deleted successfully from WordPress: {$venueReference->wrd_id} for club: {$club->url}", $this->venue_entity_type, $club->id, $venueReference->ref_id);
                Log::info("Venue deleted successfully from WordPress: wrd_id = {$venueReference->wrd_id}, club_url = {$club->url}");

                // Delete the reference from the local database
                $venueReference->delete();
            }
        } catch (ClientException $e) {
            $errorMessage = $e->getResponse()->getBody()->getContents();
            SyncerLog::logMessage('error', "Failed to delete venue {$venueReference->wrd_id} from WordPress site at {$club->url}: " . $errorMessage, $this->venue_entity_type, $club->id, $venueReference->ref_id);
            Log::error("Failed to delete venue from WordPress: wrd_id = {$venueReference->wrd_id}, club_url = {$club->url}, error = {$errorMessage}");
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            SyncerLog::logMessage('error', "Failed to delete venue {$venueReference->wrd_id} from WordPress site at {$club->url}: " . $errorMessage, $this->venue_entity_type, $club->id, $venueReference->ref_id);
            Log::error("Failed to delete venue from WordPress: wrd_id = {$venueReference->wrd_id}, club_url = {$club->url}, error = {$errorMessage}");
        }
    }
    }
}
