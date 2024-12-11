<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\golf_club;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{

    public function index(Request $request)
    {
        // Get all active golf clubs
        $clubs = golf_club::where('status', true)->get();

        // Initialize the logs query with necessary joins and ordering
        $logsQuery = DB::table('syncer_logs')
            ->leftjoin('golf_club', 'syncer_logs.club_id', 'golf_club.id')
            ->orderBy('syncer_logs.id', 'desc');

        // Apply club filter if provided
        if ($request->filled('club_filter')) {
            $logsQuery->where('club_id', $request->input('club_filter'));
        }

        // Paginate the logs query
        $logs = $logsQuery->paginate(10);

        // Return the view with logs and clubs data
        return view('logs.log', compact('logs', 'clubs'));
    }

}
