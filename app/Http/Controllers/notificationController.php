<?php

namespace App\Http\Controllers;

use App\Models\golf_club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;


class notificationController extends Controller
{
    public function index(Request $request)
{
    // Get active golf clubs with their names
    $clubs = golf_club::where('status', true)->get();

    // Construct the logs query with necessary joins and ordering
    $logsQuery = DB::table('syncer_logs as sl')
        ->join(DB::raw('(SELECT club_id, entity_type, MAX(created_at) AS latest_time
                        FROM syncer_logs
                        WHERE message_type = "error"
                        GROUP BY club_id, entity_type) latest_logs'), function ($join) {
            $join->on('sl.club_id', '=', 'latest_logs.club_id')
                 ->on('sl.entity_type', '=', 'latest_logs.entity_type')
                 ->on('sl.created_at', '=', 'latest_logs.latest_time');
        })
        ->leftJoin('notifications as n', 'sl.id', '=', 'n.log_id')
        ->leftJoin('golf_club', 'sl.club_id', '=', 'golf_club.id')
        ->where('sl.message_type', 'error') // Ensure only error messages are considered
        ->select(
            'golf_club.club_name', 
            'sl.*', 
            'n.*'
        )
        ->orderBy('sl.created_at', 'desc');

    // Paginate the logs query
    $notificationLogs = $logsQuery->paginate(10);

    return view('Notification.notification', compact('clubs', 'notificationLogs'));
}


    public function update(Request $request)
{


    $notification = Notification::find($request->id);

    if ($notification) {
        
        $notification->update(['read' => $request->read]);

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 404);
}
}
