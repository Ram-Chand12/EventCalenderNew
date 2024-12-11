<?php

namespace App\Traits;

use GuzzleHttp\Client;
use App\Models\golf_club;
use App\Models\wordpress_reference;
use App\Models\category;
use App\Traits\SyncerLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Crypt;

trait CategorySyncer
{
    protected $category_entity_type = 'category';

    public function syncCategory()
    {
        $this->categoryInsert();
        $this->deleteOrphanedCategories();
    }

    public function categoryInsert()
    {
        Log::info('Category Sync Start' );

        $golfClubs = golf_club::where('status', 1)->get();

        foreach ($golfClubs as $club) {
            if ($this->checkCategoryWebsite($club->url)) {
                $this->syncCategoryToWordPress($club);
            } else {
                SyncerLog::logMessage('info', "URL is not correct: $club->url", $this->category_entity_type, $club->id);

            }
        }
    }

    //check the existance of club url
    private function checkCategoryWebsite($url)
    {
        try {
            $client = new Client();
            $response = $client->get($url);
            return $response->getStatusCode() == 200;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to connect to WordPress site at $url: " . $e->getMessage(), $this->category_entity_type);
            return false;
        }
    }

    //sync categoryt to wordpress
    private function syncCategoryToWordPress($club)
    {
        $client = new Client();
        $wordpressUrl = rtrim($club->url, '/') . '/wp-json/tribe/events/v1/categories';

        

        $categories = category::where('status', 1)->get();

        foreach ($categories as $category) {
            $categoryRefData = wordpress_reference::where('ref_id', $category->id)
                ->where('entity_type', $this->category_entity_type)
                ->where('club_id', $club->id)
                ->first();

            if ($categoryRefData && $categoryRefData != '') {
                if ($categoryRefData->no_of_tries < 3) {
                    if ($this->checkCategoryExistsOnWordPress($club, $category)) {
                        if ($this->needsCategoryUpdating($category, $categoryRefData)) {
                            $responseBody = $this->updateCategoryOnWordPress($client, $wordpressUrl, $club, $categoryRefData->wrd_id, $category);
                            $this->updateCategoryReference($categoryRefData, $responseBody);
                        } else {
                            $categoryRefData->increment('no_of_tries');
                            $categoryRefData->touch();
                        }
                    } else {
                        $responseBody = $this->createCategoryOnWordPress($client, $wordpressUrl, $club, $category);
                        $this->updateCategoryReference($categoryRefData, $responseBody);
                    }
                }
            } else {
                if (!$this->checkCategoryExistsOnWordPress($club, $category)) {
                    $responseBody = $this->createCategoryOnWordPress($client, $wordpressUrl, $club, $category);
                    if ($responseBody) {
                        wordpress_reference::create([
                            'wrd_id' => $responseBody->id,
                            'club_id' => $club->id,
                            'ref_id' => $category->id,
                            'entity_type' => $this->category_entity_type,
                            'no_of_tries' => 1,
                            'status' => 3,
                        ])->touch();
                    }
                }
            }
        }
    }

    //check the existance of category
    private function checkCategoryExistsOnWordPress($club, $category)
    {
        try {
            $categories = wordpress_reference::where('ref_id', $category->id)
                ->where('club_id', $club->id)
                ->where('entity_type', $this->category_entity_type)
                ->first();
            return $categories ? true : false;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to check if category exists on WordPress site at {$club->url}: " . $e->getMessage(), $this->category_entity_type, $club->id, $category->id);
            return false;
        }
    }

    // function to create category on wordpress
    private function createCategoryOnWordPress($client, $wordpressUrl, $club, $category)
    {
        if($category->id>40){
        try {

            $data = [
                'name' => $category->name,
            ];

            if ($category->parent) {
                if ($this->getWordPressCategoryId($club, $category->parent) != '' && $this->getWordPressCategoryId($club, $category->parent) != 0) {
                    $data['parent'] = $this->getWordPressCategoryId($club, $category->parent);
                }
            }
            $clubPassword = Crypt::decryptString($club->password);

            $response = $client->post($wordpressUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword),
                ],
                'json' => $data,
            ]);

            $responseBody = json_decode($response->getBody());
            SyncerLog::logMessage('info', "Category created successfully: {$category->name} for club: {$club->url}", $this->category_entity_type, $club->id, $category->id, $responseBody->id);

            return $responseBody;
        } catch (ClientException $e) {
            Log::info("Failed to create category {$category->name} to WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents());
            SyncerLog::logMessage('error', "Failed to create category {$category->name} to WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents(), $this->category_entity_type, $club->id, $category->id);
            return null;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to create category {$category->name} on WordPress site at {$club->url}: " . $e->getMessage(), $this->category_entity_type, $club->id, $category->id);
            return null;
        }
    }
}

        // function to update category on wordpress
    private function updateCategoryOnWordPress($client, $wordpressUrl, $club, $wordpressCategoryId, $category)
    {

        
        try {

            $data = [
                'name' => $category->name,
            ];


            if ($category->parent) {

                $data['parent'] = $this->getWordPressCategoryId($club, $category->parent);
            }
            $clubPassword = Crypt::decryptString($club->password);

            $response = $client->put($wordpressUrl . '/' . $wordpressCategoryId, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword),
                ],
                'json' => $data,
            ]);

            $responseBody = json_decode($response->getBody());
            SyncerLog::logMessage('info', "Category updated successfully: {$category->name} for club: {$club->url}", $this->category_entity_type, $club->id, $category->id, $responseBody->id);

            return $responseBody;
        } catch (ClientException $e) {
            SyncerLog::logMessage('error', "Failed to update category {$category->name} on WordPress site at {$club->url}: " . $e->getResponse()->getBody()->getContents(), $this->category_entity_type, $club->id, $category->id);
            return null;
        } catch (\Exception $e) {
            SyncerLog::logMessage('error', "Failed to update category {$category->name} on WordPress site at {$club->url}: " . $e->getMessage(), $this->category_entity_type, $club->id, $category->id);
            return null;
        }
    }

    private function needsCategoryUpdating($category, $categoryRefData)
    {
        return $category->updated_at > $categoryRefData->updated_at;
    }

    private function updateCategoryReference($categoryRefData, $responseBody)
    {
        if ($responseBody) {
            $categoryRefData->update([
                'no_of_tries' => 1,
                'status' => 3,
                'updated_at' => now()
            ]);
            $categoryRefData->touch();
        } else {
            $categoryRefData->increment('no_of_tries');
            $categoryRefData->touch();
        }
    }

        // function to remove category from wordpress
    private function deleteOrphanedCategories()
    {

        $categoryReferences = wordpress_reference::where('entity_type', $this->category_entity_type)->get();

        foreach ($categoryReferences as $categoryReference) {


            $categoryExists = Category::find($categoryReference->ref_id);

            if (!$categoryExists) {
                

                $club = golf_club::where('status', 1)->where('id',$categoryReference->club_id)->first();
                if ($club) {
                    
                    $this->deleteCategoryFromWordPress($club, $categoryReference);
                } 
            }
        }

     
    }

    // function to remove category from wordpress
    private function deleteCategoryFromWordPress($club, $categoryReference)
    {
        if($categoryReference->ref_id>40){
        $client = new Client();
        $wordpressUrl = rtrim($club->url, '/') . '/wp-json/tribe/events/v1/categories/' . $categoryReference->wrd_id;
        $clubPassword = Crypt::decryptString($club->password);

        try {
            $response = $client->delete($wordpressUrl, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($club->username . ":" . $clubPassword)
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                SyncerLog::logMessage('info', "Category deleted successfully from WordPress: {$categoryReference->wrd_id} for club: {$club->url}", $this->category_entity_type, $club->id, $categoryReference->ref_id);
                Log::info("Category deleted successfully from WordPress: wrd_id = {$categoryReference->wrd_id}, club_url = {$club->url}");

                $categoryReference->delete();
            }
        } catch (ClientException $e) {
            $errorMessage = $e->getResponse()->getBody()->getContents();
            SyncerLog::logMessage('error', "Failed to delete category {$categoryReference->wrd_id} from WordPress site at {$club->url}: " . $errorMessage, $this->category_entity_type, $club->id, $categoryReference->ref_id);
            Log::error("Failed to delete category from WordPress: wrd_id = {$categoryReference->wrd_id}, club_url = {$club->url}, error = {$errorMessage}");
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            SyncerLog::logMessage('error', "Failed to delete category {$categoryReference->wrd_id} from WordPress site at {$club->url}: " . $errorMessage, $this->category_entity_type, $club->id, $categoryReference->ref_id);
            Log::error("Failed to delete category from WordPress: wrd_id = {$categoryReference->wrd_id}, club_url = {$club->url}, error = {$errorMessage}");
        }
    }
    }

    //function to get the category id 
    private function getWordPressCategoryId($club, $localParentCategoryId)
    {
        $parentCategoryRef = wordpress_reference::where('ref_id', $localParentCategoryId)
            ->where('club_id', $club->id)
            ->where('entity_type', $this->category_entity_type)
            ->first();

        return $parentCategoryRef ? $parentCategoryRef->wrd_id : 0;
    }
}
