<?php
namespace App\Http\Controllers;

use App\Models\wordpress_reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyncerController extends Controller
{
    public function getUserHistory(Request $request)
    {
        $refId = $request->input('ref_id');
        $entityType = $request->input('entity_type');

        // Validate the request input
        $request->validate([
            'ref_id' => 'required|integer',
            'entity_type' => 'required|string'
        ]);


        $results = DB::table('syncer_logs')
        ->select(
            'wordpress_reference.wrd_id',
            'wordpress_reference.club_id',
            'wordpress_reference.ref_id',
            'wordpress_reference.no_of_tries',
            'wordpress_reference.status',
            'golf_club.club_name',
            'syncer_logs.message',
            'syncer_logs.message_type',
            'syncer_logs.updated_at',
            'syncer_logs.created_at',
            DB::raw('wordpress_reference.created_at AS wp_created_at'),
            'syncer_logs.club_id AS syncer_club_id'
        )
        ->leftJoin('wordpress_reference', function($join) {
            $join->on('wordpress_reference.ref_id', '=', 'syncer_logs.ref_id')
                 ->on('wordpress_reference.club_id', '=', 'syncer_logs.club_id');
        })
        ->leftJoin('golf_club', 'syncer_logs.club_id', '=', 'golf_club.id')
        ->joinSub(
            DB::table('syncer_logs')
                ->select('club_id', DB::raw('MAX(updated_at) AS max_updated_at'))
                ->where('entity_type', 'user')
                ->where('ref_id', '1061')
                ->groupBy('club_id'),
            'max_syncer_logs',
            function ($join) {
                $join->on('syncer_logs.club_id', '=', 'max_syncer_logs.club_id')
                     ->on('syncer_logs.updated_at', '=', 'max_syncer_logs.max_updated_at');
            }
        )
        ->where('syncer_logs.entity_type', 'user')
        ->where('syncer_logs.ref_id', '1061')
        ->orderByDesc('syncer_logs.updated_at')
        ->get();
        return response()->json($results);
    }
}