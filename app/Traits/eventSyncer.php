<?php

namespace App\Traits;

use App\Models\event;
use App\Models\event_clubs;
use GuzzleHttp\Client;
use App\Models\golf_club;
use App\Models\wordpress_reference;
use App\Traits\SyncerLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Crypt;



trait eventSyncer
{

    protected $event_entity_type = 'events';

    public function syncEvent()
    {
        $this->eventInsert();
        $this->deleteOrphanedEvent();
    }

    // function to check club url
    public function eventInsert()
    {
       
        Log::info('Event Sync Start' );
            $golfClubs = golf_club::where('status', 1)->get();
            
        foreach ($golfClubs as $club) {

            if ($this->checkeventWebsites($club)) {
                $this->synceventToWordPress($club);
            } else {
                SyncerLog::logMessage('info', "URL is not correct: $club->url", $this->event_entity_type, $club->id);
            }
        }
    }

    // function to check club url
    private function checkeventWebsites($club)
    {
        try {
            $client = new Client();
            $response = $client->get($club->url);
            return $response->getStatusCode() == 200;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to connect to WordPress site at $club->url: " . $e->getMessage(), $this->event_entity_type, $club->id);
            return false;
        }
    }

    //sync event to wordpress
    private function synceventToWordPress($club)
    {
        $client = new Client();
        $wordpressUrl = rtrim($club->url, '/') . '/wp-json/tribe/events/v1/events';
        $events = DB::table('event_clubs')
            ->join('events', 'events.id', '=', 'event_clubs.event_id')
            ->where('golf_club_id', $club->id)
            ->get();

        foreach ($events as $event) {

            $eventRefData = wordpress_reference::where('ref_id', $event->id)
                ->where('entity_type', $this->event_entity_type)
                ->where('club_id', $club->id)
                ->first();
         
            if ($eventRefData && $eventRefData != '') {
                if ($eventRefData->no_of_tries < 3) {
                   
                    if ($this->needseventUpdating($event, $eventRefData)) {
                        $responseBody = $this->updateEventOnWordPress($client, $wordpressUrl, $club, $eventRefData->wrd_id, $event);
                        $this->updateeventReference($eventRefData, $responseBody);
                    } else {                        
                        $eventRefData->increment('no_of_tries');
                        $eventRefData->touch();
                    }
                } else {
                    Log::warning('Exceeded maximum number of tries for event', ['event_id' => $event->id, 'tries' => $eventRefData->no_of_tries]);
                }
            } else {
                if (!$this->checkeventExistsOnWordPress($club, $event)) {
                    $responseBody = $this->createeventOnWordPress($client, $wordpressUrl, $club, $event);
                    if ($responseBody) {
                        
                        wordpress_reference::create([
                            'wrd_id' => $responseBody->id,
                            'club_id' => $club->id,
                            'ref_id' => $event->id,
                            'entity_type' => $this->event_entity_type,
                            'no_of_tries' => 1,
                            'status' => 3,
                        ])->touch();
                    }
                }
            }
        }
    }



    //check the existance of event in worpress
    private function checkeventExistsOnWordPress($club, $event)
    {
        try {
            $event = wordpress_reference::where('ref_id', $event->id)
                ->where('club_id', $club->id)
                ->where('entity_type', $this->event_entity_type)
                ->first();
            return $event ? true : false;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to check if event exists on WordPress site at {$club->url}: " . $e->getMessage(), $this->event_entity_type, $club->id, $event->id);
            return false;
        }
    }


    // checking Organiser exist or not if not then create it 

    private function checkOrganiserExistsOnWordPressevent($club, $organiser)
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

    // checking Organiser exist
    private function createOrganiserOnWordPressevent($client, $wordpressUrl, $club, $organiser)
    {
        
        try {
            $clubPassword = Crypt::decryptString($club->password);
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

    // check author/ user is exist or not if not then create the user 
    private function checkUserExistsOnWordPressevent($client, $wordpressUrl, $club, $email, $user_id)
    {
        try {
            $clubPassword = Crypt::decryptString($club->password);

            $response = $client->get($wordpressUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword)
                ],
                'query' => ['search' => $email]
            ]);

            $users = json_decode($response->getBody(), true);
            SyncerLog::logMessage('info', "Checking user existence: " . !empty($users)); // Log info message

            return !empty($users);
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to {$club->email} check if user exists on WordPress site at {$club->url}: {$club->username} : {$clubPassword}" . $e->getMessage(), $this->entity_type, $club->id, $user_id); // Log error message
            return false;
        }
    }

    // create the user on wordpress
    private function createUserOnWordPressevent($client, $wordpressUrl, $club, $user)
    {
        $decryptPassword = Crypt::decryptString($user->password);
        $clubPassword = Crypt::decryptString($club->password);
      
        try {
            $response = $client->post($wordpressUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword)
                ],
                'json' => [
                    'username' => $user->name,
                    'password' => $decryptPassword,
                    'email' => $user->email,
                    'first_name' => $user->fname,
                    'last_name' => $user->lname,
                    // 'roles' => $user->role,
                ]
            ]);

            $responseBody = json_decode($response->getBody());

            SyncerLog::logMessage('info', "User created successfully user email is : {$user->email} and club is {$club->url}", $this->entity_type, $club->id, $user->id, $responseBody->id);

            return $responseBody;
        } catch (ClientException $e) {
            SyncerLog::logMessage('error', "Failed to create  user {$user->name} to WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents(), $this->entity_type, $club->id, $user->id); // Log error message
            return null;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to creaet user {$user->name} on WordPress site at {$club->url}: " . $e->getMessage(), $this->entity_type, $club->id, $user->id); // Log error message
            return null;
        }
    }

    // check venue is exist or not if not then create the organiser 
    private function checkVenueExistsOnWordPressevent($club, $venue)
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

    private function createVenueOnWordPressevent($client, $wordpressUrl, $club, $venue)
    {
        try {
            $clubPassword = Crypt::decryptString($club->password);

            $response = $client->post($wordpressUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword),
                ],
                'json' => [
                    'venue' => $venue->name,
                    'description' => $venue->vanue_description,
                    'address' => $venue->address,
                    'city' => $venue->city,
                    'Zip' => $venue->postal_code,
                    'phone' => $venue->phone,
                    'country' => $venue->country,
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



    private function createEventOnWordPress($client, $wordpressUrl, $club, $event)
    {
        if ($event->id > 916) {
            try {
                


                $event_data_array = $event_data = DB::table('events')
                    ->leftJoin('event_metas', 'event_metas.event_id', '=', 'events.id')
                    ->where('events.id', $event->id)
                    ->get();

                    
                $event_data_array = $event_data->toArray();

                // Initialize arrays for event details and meta data
                $eventDetails = [];
                $eventMeta = [];

                $entityIds = ['venue' => null, 'organiser' => null, 'user' => null];
                log::info(json_encode($event_data_array));
                // Process the event data into details and meta arrays
                foreach ($event_data_array as $row) {
                    
                    $eventDetails['event_id'] = $row->event_id;
                    $eventDetails['name'] = $row->name;
                    $eventDetails['description'] = $row->description;
                    $eventDetails['status'] = $row->status;

                    if ($row->meta_key == 'Author') {
                        $eventDetails['author'] = $row->meta_value;
                    } else if ($row->meta_key == 'Organizer') {
                        $eventDetails['Organizer'] = $row->meta_value;
                    } else if ($row->meta_key == 'Venue') {
                        $eventDetails['Venue'] = $row->meta_value;
                    } else if ($row->meta_key == 'Event Category') {
                        $eventDetails['Event Category'] = $row->meta_value;
                    } else {
                        $eventMeta[$row->meta_key] = $row->meta_value;
                    }
                }
             
                // Ensure the image value is a string
                log::info(json_encode($eventMeta));
                $imagePaths=" ";
                if(isset($eventMeta['Image'])){
                    if ($eventMeta['Image'] && strpos($eventMeta['Image'], 'http') !== 0) {
                        //     // Append the domain dynamically using the url() helper function
                        $imagePaths = url($eventMeta['Image']);
                    }else{
                        $imagePaths = url($eventMeta['Image']);
                    }
                }else{
                    $imagePaths="https://arcisgolf.com/wp-content/uploads/2024/02/Arcis-Golf-Side-By-Side-300x105.webp";
                }

                // log::info($eventMeta['Image'].'inage path');
                $status = $eventDetails['status'];

                if ($status == 'publish_immediately') {

                    $status = 'publish';
                } else if ($status == 'publish_later') {

                    $status = 'future';
                    $date = $eventMeta['publish_time'];
                } else {
                    $status = 'draft';
                }
                
                $url = null;
                if (isset($eventMeta['Website Url'])) {
                    if (strpos($eventMeta['Website Url'], 'http://') !== 0 && strpos($eventMeta['Website Url'], 'https://') !== 0) {
                        $url = 'https://' . $url;
                    }
                }

                log::info(json_encode($eventDetails));
                if ($eventDetails['Venue'] != null) {
                    $venue_wrd_id = $this->get_wrd_id($eventDetails['Venue'], $club->id, 'venue');
                }
                if ($eventDetails['author'] != null) {
                    $user_wrd_id = $this->get_wrd_id($eventDetails['author'], $club->id, 'user');
                }
                if ($eventDetails['Organizer'] != null) {
                    $organiser_wrd_id = $this->get_wrd_id($eventDetails['Organizer'], $club->id, 'organizer');
                }
                $category_wrd_id=0;
                if (isset($eventDetails['Event Category']) && $eventDetails['Event Category'] != null) {
                    $category_wrd_id = $this->get_category_wrd_id($eventDetails['Event Category'], $club->id, 'category');
                }


                $data = [
                    'title' => $eventDetails['name'],
                    'description' => $eventDetails['description'],
                    'start_date' => $eventMeta['Start_date'] ?? null,
                    'end_date' => $eventMeta['End_date'] ?? null,
                    'excerpt' => $eventMeta['Excerpt'] ?? ' ',
                    'venue' => $venue_wrd_id,
                    'author' => $user_wrd_id,
                    'organizer' => $organiser_wrd_id,
                    'website' => $url,
                    'currency_code' => 'USD', // Currency code
                    'currency_position' => 'after', // Currency position (before or after)
                    'cost' => $eventMeta['Cost Type'] == 1 ? $eventMeta['Price Symbol'] ?? '$' . $eventMeta['Price'] : $eventMeta['Price'] . $eventMeta['Price Symbol'] ?? '$',
                    'image' => $imagePaths,
                    'status' => $status,
                    'categories' => $category_wrd_id,
                    'currency_symbol' => $eventMeta['Currency_symbol'] ?? '$',
                ];


                if (isset($date)) {
                    $data['date'] = $date;
                }

                if (empty($data['title']) || empty($data['start_date']) || empty($data['end_date'])) {
                    SyncerLog::logMessage('error', "Missing required parameters for event: {$event->id}", $this->event_entity_type, $club->id, $event->id);
                    return null;
                }

                $clubPassword = Crypt::decryptString($club->password);
                // Ensure the password is provided
                if (empty($clubPassword)) {
                    Log::error("The password for the WordPress API request is empty.");
                    return null;
                }

                // Send the request to the WordPress API
                
                $response = $client->post($wordpressUrl, [
                    'headers' => [
                        'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $data,
                ]);

                // Parse the response
                $responseBody = json_decode($response->getBody());
                Log::info("Event created successfully:", ['response' => $responseBody]);
                SyncerLog::logMessage('info', "Event created successfully: {$event->name} for club: {$club->url}", $this->event_entity_type, $club->id, $event->id, $responseBody->id);

                return $responseBody;
            } catch (ClientException $e) {
                $errorMessage = $e->getResponse()->getBody()->getContents();
                Log::error("ClientException: Failed to create event {$event->name} on WordPress site at {$club->url}: " . $errorMessage);
                // SyncerLog::logMessage('error', "Failed to create event {$event->name} on WordPress site at {$club->url}: " . $errorMessage, $this->event_entity_type, $club->id, $event->id);    

                SyncerLog::logMessage(
                    'error',
                    "Failed to create event {$event->name} on WordPress site at {$club->url}: " . $errorMessage,
                    $this->event_entity_type,
                    $club->id,
                    $event->id,
                    $event->id,
                    ['error' => $errorMessage]
                );
                return null;
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                Log::error("Exception: Failed to create event {$event->name} on WordPress site at {$club->url}: " . $errorMessage);
                SyncerLog::logMessage('error', "Failed to create event {$event->name} on WordPress site at {$club->url}: " . $errorMessage, $this->event_entity_type, $club->id, $event->id);
                return null;
            }
        }
    }


    function ensure_https($url)
    {
        if (strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0) {
            return 'https://' . $url;
        }
        return $url;
    }


    private function get_wrd_id($ref_id, $club_id, $entitytype)
    {
        $ref_record = wordpress_reference::where('ref_id', $ref_id)
            ->where('club_id', $club_id)
            ->where('entity_type', $entitytype)
            ->first();

        $wrd_id = 0; // Initialize $wrd_id to null by default
       
        if ($ref_record) {

            // Check if wrd_id exists and is not empty
            if (isset($ref_record->wrd_id) && !empty($ref_record->wrd_id)) {

                $wrd_id = $ref_record->wrd_id;
            }
        }

        // Log the wrd_id and entity type
        

        return $wrd_id;
    }
    private function get_category_wrd_id($ref_id, $club_id, $entitytype)
    {
        $meta_values = explode(',', $ref_id);
        $results = [];
        
        foreach ($meta_values as $ref_id) {
            // Query for each $ref_id and fetch data
            $ref_record = wordpress_reference::where('ref_id', $ref_id)
                ->where('club_id', $club_id)
                ->where('entity_type', $entitytype)
                ->select('wrd_id')
                ->first();

            if ($ref_record) {
                // If record found, add it to results array
                $results[] = $ref_record->wrd_id;
            }
        }
        
        return $results;
    }




    private function updateEventOnWordPress($client, $wordpressUrl, $club, $wordPressEventId, $event)
    {
       

        if ($event->id > 916) {
            try {
              

                $event_data_array = $event_data = DB::table('events')
                    ->leftJoin('event_metas', 'event_metas.event_id', '=', 'events.id')
                    ->where('events.id', $event->id)
                    ->get();

                $event_data_array = $event_data->toArray();
                // Initialize arrays for event details and meta data
                $eventDetails = [];
                $eventMeta = [];
                $event_category = [];


                foreach ($event_data_array as $row) {

                    $eventDetails['event_id'] = $row->event_id;
                    $eventDetails['name'] = $row->name;
                    $eventDetails['description'] = $row->description;
                    $eventDetails['status'] = $row->status;
                    // $eventDetails['golf_club_id'] = $row->golf_club_id;

                    if ($row->meta_key == 'Author') {
                        $eventDetails['author'] = $row->meta_value;
                    } else if ($row->meta_key == 'Organizer') {
                        $eventDetails['Organizer'] = $row->meta_value;
                    } else if ($row->meta_key == 'Venue') {
                        $eventDetails['Venue'] = $row->meta_value;
                    } else if ($row->meta_key == 'Event Category') {
                        $eventDetails['Event Category'] = $row->meta_value;
                    } else {
                        $eventMeta[$row->meta_key] = $row->meta_value;
                    }
                }
                log::info(json_encode($eventMeta));

                $imagePaths="";
                if(isset($eventMeta['Image'])){
                    if ($eventMeta['Image'] && strpos($eventMeta['Image'], 'http') !== 0) {
                        //     // Append the domain dynamically using the url() helper function
                        $imagePaths = url($eventMeta['Image']);
                    }else{
                        $imagePaths = url($eventMeta['Image']);
                    }
                }else{
                    $imagePaths="https://arcisgolf.com/wp-content/uploads/2024/02/Arcis-Golf-Side-By-Side-300x105.webp";
                }
     

                $status = $eventDetails['status'];
              

                if ($status == 'publish_immediately') {

                    $status = 'publish';
                } else if ($status == 'publish_later') {

                    $status = 'future';
                    $date = $eventMeta['publish_time'];
                } else if ($status == 'trash') {

                    $status = 'trash';
                } else {
                    $status = 'draft';
                }

                $url = null;
                if (isset($eventMeta['Website Url'])) {
                    if (strpos($eventMeta['Website Url'], 'http://') !== 0 && strpos($eventMeta['Website Url'], 'https://') !== 0) {
                        $url = 'https://' . $url;
                    }
                }

                if ($eventDetails['Venue'] != null) {
                    $venue_wrd_id = $this->get_wrd_id($eventDetails['Venue'], $club->id, 'venue');
                }
                if ($eventDetails['author'] != null) {
                    $user_wrd_id = $this->get_wrd_id($eventDetails['author'], $club->id, 'user');
                }
                if ($eventDetails['Organizer'] != null) {
                    $organiser_wrd_id = $this->get_wrd_id($eventDetails['Organizer'], $club->id, 'organiser');
                }
                $category_wrd_id=0;
                if (isset($eventDetails['Event Category']) && $eventDetails['Event Category'] != null) {
                    $category_wrd_id = $this->get_category_wrd_id($eventDetails['Event Category'], $club->id, 'category');
                }
          

                $data = [
                    'title' => $eventDetails['name'],
                    'description' => $eventDetails['description'],
                    'start_date' => $eventMeta['Start_date'] ?? null,
                    'end_date' => $eventMeta['End_date'] ?? null,
                    'excerpt' => $eventMeta['Excerpt'] ?? ' ',
                    'venue' => $venue_wrd_id,
                    'author' => $user_wrd_id,
                    'organizer' => $organiser_wrd_id,
                    'website' => $url,
                    'currency_symbol' => $eventMeta['Currency_symbol'] ?? '$',
                    'cost' => $eventMeta['Cost Type'] == 1 ? $eventMeta['Price Symbol'] ?? '$' . $eventMeta['Price'] : $eventMeta['Price'] . $eventMeta['Price Symbol'] ?? '$',
                    // "cost" => $eventMeta['Price'] ?? null,
                    'image' => $imagePaths,
                    // 'image' => isset($imagePaths) ? $imagePaths : ' ',
                    'status' => $status,
                    // 'publish_time' => $publishTime,
                    'categories' => $category_wrd_id,

                ];

                if (isset($date)) {
                    $data['date'] = $date;
                }


                // Ensure required parameters are present
                if (empty($data['title']) || empty($data['start_date']) || empty($data['end_date'])) {
                    SyncerLog::logMessage('error', "Missing required parameters for event: {$event->id}", $this->event_entity_type, $club->id, $event->id);
                    return null;
                }

                $clubPassword = Crypt::decryptString($club->password);
                // Ensure the password is provided
                if (empty($clubPassword)) {
                    Log::error("The password for the WordPress API request is empty.");
                    return null;
                }


                $wordpressApiUrl = rtrim($wordpressUrl, '/') . '/' . $wordPressEventId;
                

                // Send the request to the WordPress API
                $response = $client->put($wordpressApiUrl, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword),
                    ],
                    'json' => $data,
                ]);

                // Parse the response
                $responseBody = json_decode($response->getBody());
                
                SyncerLog::logMessage('info', "Event updated successfully: {$event->name} for club: {$club->url}", $this->event_entity_type, $club->id, $event->id, $responseBody->id);

                return $responseBody;
            } catch (ClientException $e) {
              
                // $errorMessage = $e->getResponse()->getBody()->getContents();
                $response = json_decode($e->getResponse()->getBody()->getContents());
                $errorMessage = isset($response->message) ? $response->message : 'Unknown error';

                Log::error("ClientException: Failed to update1 event {$event->name} on WordPress site at {$club->url}", ['error' => $errorMessage]);
                // SyncerLog::logMessage('error', "Failed to update3 event {$event->name} on WordPress site at {$club->url}", ['error' => $errorMessage],(string) $this->event_entity_type, $club->id, $event->id);
                SyncerLog::logMessage(
                    'error',
                    "Failed to update event {$event->name} on WordPress site at {$club->url}",
                    $this->event_entity_type,
                    $club->id,
                    $event->id,
                    $event->id,
                    ['error' => $errorMessage]
                );
                return null;
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                Log::error("Exception: Failed to update2 event {$event->name} on WordPress site at {$club->url}", ['error' => $errorMessage]);
                SyncerLog::logMessage('error', "Failed to update4 event {$event->name} on WordPress site at {$club->url}", $this->event_entity_type, $club->id, $event->id, $wordPressEventId, [$errorMessage]);
                return null;
            }
        }
    }


    private function needseventUpdating($event, $eventRefData)
    {

        return $event->updated_at > $eventRefData->updated_at;
    }

    private function updateeventReference($eventRefData, $responseBody)
    {
        if ($responseBody) {
            $eventRefData->update([
                'no_of_tries' => 1,
                'status' => 3,
                'updated_at' => now()
            ]);
            $eventRefData->touch();
        } else {
            $eventRefData->increment('no_of_tries');
            $eventRefData->touch();
        }
    }


    //remove event on wordpress
    private function deleteOrphanedEvent()
    {
        

        $eventReferences = wordpress_reference::where('entity_type', $this->event_entity_type)->get();

        foreach ($eventReferences as $eventReference) {
            $eventExists = event::find($eventReference->ref_id);
            if (!$eventExists) {
                
                $club = golf_club::find($eventReference->club_id);
                if ($club) {
                    Log::info("Deleting event from WordPress: wrd_id = {$eventReference->wrd_id}, club_url = {$club->url}");
                    $this->deleteEventFromWordPress($club, $eventReference);
                } else {
                    // Log::warning("Club not found for event reference: club_id = {$eventReference->club_id}");
                }
            }
        }

        
    }

    //remove event on wordpress
    private function deleteEventFromWordPress($club, $eventReference)
    {
        if ($eventReference->ref_id > 894) {
            $client = new Client();

            // Construct the WordPress API endpoint URL
            $wordpressUrl = rtrim($club->url, '/') . '/wp-json/tribe/events/v1/events/' . $eventReference->wrd_id;
            $clubPassword = Crypt::decryptString($club->password);

            try {
                // Send a DELETE request to the WordPress API endpoint

                $response = $client->delete($wordpressUrl, [
                    'headers' => [
                        'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword)
                    ],
                ]);

                // Check the response status code
                if ($response->getStatusCode() == 200) {
                    // If successful, log the event deletion and delete the event reference
                    SyncerLog::logMessage('info', "Event deleted successfully from WordPress: {$eventReference->wrd_id} for club: {$club->url}", $this->event_entity_type, $club->id, $eventReference->ref_id);
                    Log::info("Event deleted successfully from WordPress: wrd_id = {$eventReference->wrd_id}, club_url = {$club->url}");

                    $eventReference->delete();
                } else {
                    // If not successful, log the error response
                    $errorBody = $response->getBody()->getContents();
                    Log::error("Failed to delete event from WordPress. Status code: {$response->getStatusCode()}, Response: {$errorBody}");
                    SyncerLog::logMessage('error', "Failed to delete event {$eventReference->wrd_id} from WordPress site at {$club->url}: " . $errorBody, $this->event_entity_type, $club->id, $eventReference->ref_id);
                }
            } catch (ClientException $e) {
                // Catch and log any client exceptions
                $errorMessage = $e->getResponse()->getBody()->getContents();
                SyncerLog::logMessage('error', "Failed to delete event {$eventReference->wrd_id} from WordPress site at {$club->url}: " . $errorMessage, $this->event_entity_type, $club->id, $eventReference->ref_id);
                Log::error("Failed to delete event from WordPress: wrd_id = {$eventReference->wrd_id}, club_url = {$club->url}, error = {$errorMessage}");
            } catch (\Exception $e) {
                // Catch and log any other exceptions
                $errorMessage = $e->getMessage();
                SyncerLog::logMessage('error', "Failed to delete event {$eventReference->wrd_id} from WordPress site at {$club->url}: " . $errorMessage, $this->event_entity_type, $club->id, $eventReference->ref_id);
                Log::error("Failed to delete event from WordPress: wrd_id = {$eventReference->wrd_id}, club_url = {$club->url}, error = {$errorMessage}");
            }
        }
    }




    private function getWordPresseventId($club, $localParenteventId)
    {
        $parenteventRef = wordpress_reference::where('ref_id', $localParenteventId)
            ->where('club_id', $club->id)
            ->where('entity_type', $this->event_entity_type)
            ->first();

        return $parenteventRef ? $parenteventRef->wrd_id : 0;
    }
}
