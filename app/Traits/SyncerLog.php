<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\syncer_log;


class SyncerLog
{
    public static function logMessage($type = null, $message = null, $entity_type = null, $club_id = 0, $ref_id = 0, $wrd_id = 0, $data = [])
    {

        $log = new syncer_log();

        $log->wrd_id = $wrd_id; 
        $log->club_id = $club_id; 
        $log->ref_id = $ref_id; 
        $log->entity_type = $entity_type; 
        $log->message_type = $type;
        $log->message = $message;
        $log->save();
        $logId = $log->id;
        // if ($type === 'error') {
        //     self::createNotification($logId);
        // }


    }

    // protected static function createNotification($logId)
    // {
    //     DB::table('notifications')->insert([
    //         'log_id' => $logId,            
    //         'read' => false, 
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);
    // }



}
