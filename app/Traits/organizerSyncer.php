<?php

namespace App\Traits;

use GuzzleHttp\Client;
use App\Models\golf_club;
use App\Models\wordpress_reference;
use App\Models\Organizer; // Changed to organiser model
use App\Traits\SyncerLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Crypt;


trait organizerSyncer
{
    protected $organiser_entity_type = 'organiser'; // Changed to organiser entity type

    public function syncOrganiser()
    {
        $this->organiserInsert();
        $this->deleteOrphanedOrganisers(); // Changed to deleteOrphanedOrganisers

    }

    public function organiserInsert()
    {
        Log::info('Organiser Sync Start ' );

        $golfClubs = golf_club::where('status', 1)->get();

        foreach ($golfClubs as $club) {
            // Check if the URL exists and is a WordPress site
            if ($this->checkOrganiserWebsite($club->url)) {
                
                $this->syncOrganiserToWordPress($club); // Changed to syncOrganiserToWordPress
            } else {
                SyncerLog::logMessage('info', "URL is not correct: $club->url", $this->organiser_entity_type, $club->id);
               
            }
        }
    }

    private function checkOrganiserWebsite($url)
    {
        try {
            $client = new Client();
            $response = $client->get($url);
            return $response->getStatusCode() == 200;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to connect to WordPress site at $url: " . $e->getMessage(), $this->organiser_entity_type);
            return false;
        }
    }

    //sync organizer on wordpress
    private function syncOrganiserToWordPress($club)
    {
        $client = new Client();
        $wordpressUrl = rtrim($club->url, '/') . '/wp-json/tribe/events/v1/organizers';
       

        // Retrieve all organisers from the local database
        $organisers = Organizer::where('status', 1)->get(); // Changed to organiser model

        foreach ($organisers as $organiser) {
            $organiserRefData = wordpress_reference::where('ref_id', $organiser->id)
                ->where('entity_type', $this->organiser_entity_type) // Changed to organiser entity type
                ->where('club_id', $club->id)
                ->first();

            if ($organiserRefData && $organiserRefData != '') {

                if ($organiserRefData->no_of_tries < 3) {

                    if ($this->checkOrganiserExistsOnWordPress($club, $organiser)) {

                        if ($this->needsOrganiserUpdating($organiser, $organiserRefData)) {

                            $responseBody = $this->updateOrganiserOnWordPress($client, $wordpressUrl, $club, $organiserRefData->wrd_id, $organiser);
                            $this->updateOrganiserReference($organiserRefData, $responseBody);
                        } else {

                            $organiserRefData->increment('no_of_tries');
                            $organiserRefData->touch();
                        }
                    } else {

                        $responseBody = $this->createOrganiserOnWordPress($client, $wordpressUrl, $club, $organiser);
                        $this->updateOrganiserReference($organiserRefData, $responseBody);
                    }
                }
            } else {

                if ($this->checkOrganiserExistsOnWordPress($club, $organiser)) {

                } else {

                    $responseBody = $this->createOrganiserOnWordPress($client, $wordpressUrl, $club, $organiser);
                    if ($responseBody) {
                        wordpress_reference::create([
                            'wrd_id' => $responseBody->id,
                            'club_id' => $club->id,
                            'ref_id' => $organiser->id,
                            'entity_type' => $this->organiser_entity_type,
                            'no_of_tries' => 1,
                            'status' => 3,
                        ])->touch();
                    }
                }
            }
        }
    }

    //check organizer existance
    private function checkOrganiserExistsOnWordPress($club, $organiser)
    {
        try {
            $organisers = wordpress_reference::where('ref_id', $organiser->id)
                ->where('club_id', $club->id)
                ->where('entity_type', $this->organiser_entity_type)
                ->first();

            return $organisers ? true : false;

        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to check if organiser exists on WordPress site at {$club->url}: " . $e->getMessage(), $this->organiser_entity_type, $club->id, $organiser->id);
            return false;
        }
    }

    //create oranizer to add on wordpress
    private function createOrganiserOnWordPress($client, $wordpressUrl, $club, $organiser)
    {
        if($organiser->id>14){
        $clubPassword = Crypt::decryptString($club->password);
       
        try {
            $response = $client->post($wordpressUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword),
                ],
                'json' => [
                    'organizer' => $organiser->name,
                    'description' => $organiser->description,
                    'email' => $organiser->email,
                    'phone' => $organiser->phone,
                    'website' => $organiser->website,
                ],
            ]);

            $responseBody = json_decode($response->getBody());
            SyncerLog::logMessage('info', "Organiser created successfully: {$organiser->name} for club: {$club->url}", $this->organiser_entity_type, $club->id, $organiser->id, $responseBody->id);
            
            return $responseBody;
        } catch (ClientException $e) {
            Log::info("Failed to create organiser {$organiser->name} to WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents());
            SyncerLog::logMessage('error', "Failed to create organiser {$organiser->name} to WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents(), $this->organiser_entity_type, $club->id, $organiser->id);
            return null;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to create organiser {$organiser->name} on WordPress site at {$club->url}: " . $e->getMessage(), $this->organiser_entity_type, $club->id, $organiser->id);
            return null;
        }
    }
    }

    //update te organizer on wordpress
    private function updateOrganiserOnWordPress($client, $wordpressUrl, $club, $wordpressOrganiserId, $organiser)
    {
        $clubPassword = Crypt::decryptString($club->password);

        try {
            $response = $client->put($wordpressUrl . '/' . $wordpressOrganiserId, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword),
                ],
                'json' => [
                    'organizer' => $organiser->name,
                    'description' => $organiser->description,
                    'email' => $organiser->email,
                    'phone' => $organiser->phone,
                    'website' => $organiser->website,
                ],
            ]);

            $responseBody = json_decode($response->getBody());
            SyncerLog::logMessage('info', "Organiser updated successfully: {$organiser->name} for club: {$club->url}", $this->organiser_entity_type, $club->id, $organiser->id, $responseBody->id);

            return $responseBody;
        } catch (ClientException $e) {
            SyncerLog::logMessage('error', "Failed to update organiser {$organiser->name} on WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents(), $this->organiser_entity_type, $club->id, $organiser->id);
            return null;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to update organiser {$organiser->name} on WordPress site at {$club->url}: " . $e->getMessage(), $this->organiser_entity_type, $club->id, $organiser->id);
            return null;
        }
    }

    private function needsOrganiserUpdating($organiser, $organiserRefData)
    {
        return $organiser->updated_at > $organiserRefData->updated_at;
    }

    private function updateOrganiserReference($organiserRefData, $responseBody)
    {
        if ($responseBody) {
            $organiserRefData->update([
                'no_of_tries' => 1,
                'status' => 3,
                'updated_at' => now()
            ]);
            $organiserRefData->touch();
        } else {
            $organiserRefData->increment('no_of_tries');
            $organiserRefData->touch();
        }
    }

    //delete organizer
    private function deleteOrphanedOrganisers()
    {


        // Retrieve all organiser references
        $organiserReferences = wordpress_reference::where('entity_type', $this->organiser_entity_type)->get();

        foreach ($organiserReferences as $organiserReference) {


            // Check if the organiser exists in the local database
            $organiserExists = Organizer::where('status', 1)->where('id',$organiserReference->ref_id)->first();

            if (!$organiserExists) {


                $club = golf_club::where('status', 1)->find($organiserReference->club_id);
                if ($club) {

                    $this->deleteOrganiserFromWordPress($club, $organiserReference);
                } else {
                    // Log::warning("Club not found for organiser reference: club_id = {$organiserReference->club_id}");
                }
            }
        }


    }

    private function deleteOrganiserFromWordPress($club, $organiserReference)
    {
        if($organiserReference->ref_id>14){

      
        $client = new Client();
        $wordpressUrl = rtrim($club->url, '/') . '/wp-json/tribe/events/v1/organizers/' . $organiserReference->wrd_id;
        $clubPassword = Crypt::decryptString($club->password);

        try {
            $response = $client->delete($wordpressUrl, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword)
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                SyncerLog::logMessage('info', "Organiser deleted successfully from WordPress: {$organiserReference->wrd_id} for club: {$club->url}", $this->organiser_entity_type, $club->id, $organiserReference->ref_id);
                Log::info("Organiser deleted successfully from WordPress: wrd_id = {$organiserReference->wrd_id}, club_url = {$club->url}");

                // Delete the reference from the local database
                $organiserReference->delete();
            }
        } catch (ClientException $e) {
            $errorMessage = $e->getResponse()->getBody()->getContents();
            SyncerLog::logMessage('error', "Failed to delete organiser {$organiserReference->wrd_id} from WordPress site at {$club->url}: " . $errorMessage, $this->organiser_entity_type, $club->id, $organiserReference->ref_id);
            Log::error("Failed to delete organiser from WordPress: wrd_id = {$organiserReference->wrd_id}, club_url = {$club->url}, error = {$errorMessage}");
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            SyncerLog::logMessage('error', "Failed to delete organiser {$organiserReference->wrd_id} from WordPress site at {$club->url}: " . $errorMessage, $this->organiser_entity_type, $club->id, $organiserReference->ref_id);
            Log::error("Failed to delete organiser from WordPress: wrd_id = {$organiserReference->wrd_id}, club_url = {$club->url}, error = {$errorMessage}");
        }
    }
    }
}
