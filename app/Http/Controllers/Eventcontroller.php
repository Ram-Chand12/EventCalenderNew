<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\event;
use App\Models\event_clubs;
use App\Models\event_meta;
use App\Models\golf_club;
use App\Models\Organizer;
use App\Models\User;
use App\Models\Vanue;
use App\Rules\ImageType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\wordpress_reference;
use App\Jobs\wordpressSyncerJob;
use League\CommonMark\Extension\DescriptionList\Node\Description;

class Eventcontroller extends Controller
{

    public function index(Request $request)
    {
        $event_clubs = golf_club::where('status', true)->get();
        $event_status = event::pluck('status')->unique()->values();

        $query = DB::table('events')
            ->leftJoin('event_clubs', 'events.id', '=', 'event_clubs.event_id')
            ->leftJoin('golf_club', 'event_clubs.golf_club_id', '=', 'golf_club.id')
            ->select('events.id', DB::raw('MAX(events.name) AS event_name'), 'events.status AS event_status')
            ->selectRaw('GROUP_CONCAT(golf_club.club_name) AS club_names')
            ->groupBy('events.id', 'events.name', 'events.status');

        if ($request->has('club_filter') && $request->input('club_filter') != '') {
            $clubName = $request->input('club_filter');
            $query->whereExists(function ($query) use ($clubName) {
                $query->select(DB::raw(1))
                    ->from('event_clubs')
                    ->join('golf_club', 'event_clubs.golf_club_id', '=', 'golf_club.id')
                    ->whereRaw('events.id = event_clubs.event_id')
                    ->where('golf_club.club_name', $clubName);
            });
        }

        if ($request->has('status_filter') && $request->input('status_filter') != '') {
            $query->where('events.status', $request->input('status_filter'));
        }

        $events = $query->orderBy('events.id', 'desc')->paginate(10);

        return view('Event.event', compact('events', 'event_clubs', 'event_status'));
    }


    public function create()
    {
        $event = event::where('status', true)->get();
        $golf_club = golf_club::where('status', true)->get();
        $vanue = Vanue::where('status', true)->get();
        $organizer = Organizer::where('status', true)->get();
        $user = User::where('status', true)
            ->where('user_type', 'wordpress user')->get();
        $categories = category::where('status', true)->get();
        $categoryTree = $this->buildCategoryTree($categories);



        return view('Event.event-add', compact('golf_club', 'vanue', 'organizer', 'user', 'categories', 'event', 'categoryTree'));
    }

    public function buildCategoryTree($categories, $parentId = null)
    {
        $categoryTree = [];
        foreach ($categories as $category) {
            if ($category->parent === $parentId) {
                $childCategories = $this->getChildCategories($category->id, $categories);
                $categoryTree[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'children' => $childCategories,
                ];
            }

        }
        return $categoryTree;
    }

    public function getChildCategories($parentId, $categories)
    {
        $childCategories = [];
        foreach ($categories as $category) {
            if ($category->parent === $parentId) {
                // dd($parentId);
                $childCategories[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'children' => $this->getChildCategories($category->id, $categories),
                ];
            }
        }
        return $childCategories;
    }


    // Usage


    public function store(request $request)
    {

        $data = $request->all();

        $validate = $request->validate([
            'venue' => 'required',
            'name' => 'required|string|max:255',
            'Currency_symbol' => 'nullable|max:20',
            'event_description' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'selected_golf_club' => 'required',
            'author' => 'required',
            'status' => 'required',
            'url' => ['nullable', 'regex:/^(https:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'],
            'cost' => 'numeric|nullable',
            'image' => ['nullable', new ImageType],
        ], [
            'selected_golf_club.required' => 'At least one golf club must be selected.',
            'publish_time.required_if' => 'Please select a publish time when choosing "Publish at a specific time".',

        ]);

        $destinationPath = 'event_feature_image';

        if ($request->hasFile('image')) {
            $myimage = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path($destinationPath), $myimage);
            $data['image'] = $destinationPath . '/' . $myimage;
        } else {

            $data['image'] = null;
        }

        $metaData = [
            'Excerpt' => $data['excerpt'],
            'Start_date' => $validate['startDate'],
            'End_date' => $validate['endDate'],
            'Venue' => $validate['venue'],
            'Organizer' => $data['organizer'] ?? '',
            'Author' => $validate['author'],
            'Website Url' => $validate['url'],
            'Price Symbol' => $validate['Currency_symbol'],
            'Cost Type' => $data['cost_type'],
            'Price' => !empty($data['cost']) ? $data['cost'] : 0,
            'Image' => $data['image'] ?? '',
            'publish_time' => $data['publish_time'] ?? '',
            'Event Category' => isset($data['event_cat']) && is_array($data['event_cat']) ? implode(',', $data['event_cat']) : null,
        ];

        $status = $validate['status'];


        if ($status === 'published') {
            $status = $data['publish_option'] ?? '';
        } elseif ($status === 'draft') {
            $status = 'draft';
        }
        $event = event::create([
            'name' => $validate['name'],
            'description' => $validate['event_description'],
            'status' => $status,
        ]);
        $eventId = $event->id;

        foreach ($validate['selected_golf_club'] as $golf_club_id) {

            event_clubs::create([
                'event_id' => $eventId,
                'golf_club_id' => $golf_club_id,

            ]);
        }

        foreach ($metaData as $key => $value) {
            if ($value !== null && $value !== '') {
                event_meta::create([
                    'event_id' => $eventId,
                    'meta_key' => $key,
                    'meta_value' => $value,
                ]);
            }
        }
        wordpressSyncerJob::dispatch();

        Alert::success('Success', 'Event has been saved !');
        return redirect()->route('event');


    }
    public function edit($id)
    {
        $event = Event::find($id);
        $golf_club = golf_club::where('status', true)->get();
        $vanue = Vanue::where('status', true)->get();
        $organizer = Organizer::where('status', true)->get();




        $user = User::where('status', true)
            ->where('role', 'administrator')
            ->where('user_type', 'wordpress user')->get();
        $categories = category::where('status', true)->get();

        $event_metas = event_meta::where('event_id', $id)->get();
        $categoryTree = $this->buildCategoryTree($categories);

        $selected_event_clubs_ids = event_clubs::where('event_id', $id)->pluck('golf_club_id')->toArray();
        $selected_event_clubs = golf_club::whereIn('id', $selected_event_clubs_ids)->get();

        $selected_event_category = explode(",", $event_metas->where('meta_key', 'Event Category')->pluck('meta_value')->first());

        $organised_event_meta = $event_metas->pluck('meta_value', 'meta_key')->toArray();


        return view('Event.event-edit', compact('golf_club', 'vanue', 'organizer', 'user', 'categories', 'event', 'organised_event_meta', 'selected_event_clubs', 'categoryTree', 'selected_event_category'));
    }

    public function update(Request $request)
    {

        $data = $request->all();

        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'event_description' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'venue' => 'required',
            'selected_golf_club' => 'required',
            'author' => 'required',
            'status' => 'required',
            'cost' => 'numeric|nullable',
            'url' => ['nullable', 'regex:/^(https:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'],
            'image' => ['nullable', new ImageType],
        ], [
            'selected_golf_club.required' => 'At least one golf club must be selected.',
            'publish_time.required_if' => 'Please select a publish time when choosing "Publish at a specific time".',

        ]);

        // dd($data);
        $event = Event::findOrFail($request->id);

        $destinationPath = 'event_feature_image';
      
        if ($request->hasFile('image')) {
         
            $myimage = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path($destinationPath), $myimage);
            $data['image'] = $destinationPath . '/' . $myimage;
            
        }
        else {
            // If no new file is uploaded, retain the old image
            $data['image'] = $request->input('old_image');
        }
        $status = $validate['status'];


        if ($status === 'published') {
            $status = $data['publish_option'] ?? '';
        } elseif ($status === 'draft') {
            $status = 'draft';
        }

        $event->update([
            'name' => $validate['name'],
            'description' => $validate['event_description'],
            'status' => $status,
        ]);

        $event->updated_at = now();
        $event->save();

        $event->clubs()->sync($data['selected_golf_club']);
 
        $metaData = [
            'Excerpt' => $data['excerpt'],
            'Start_date' => $validate['startDate'],
            'End_date' => $validate['endDate'],
            'Venue' => $validate['venue'],
            'Organizer' => $data['organizer'] ?? '',
            'Author' => $validate['author'],
            'Website Url' => $validate['url'],
            'Price Symbol' => $data['Currency_symbol'],
            'Cost Type' => $data['cost_type'],
            'Price' => !empty($data['cost']) ? $data['cost'] : 0,
            'publish_time' => $data['publish_time'] ?? '',
            'Image' => isset($data['image']) ? $data['image'] : null,
            'Event Category' => isset($data['event_cat']) && is_array($data['event_cat']) ? implode(',', $data['event_cat']) : null,
        ];

        foreach ($metaData as $key => $value) {

            event_meta::updateOrCreate(
                ['event_id' => $event->id, 'meta_key' => $key],
                ['meta_value' => $value]
            );

        }


        wordpress_reference::updateWithoutTimestamp(
            [
                'status' => 1,
                'no_of_tries' => 1,
            ],
            [
                'ref_id' => $request->id,
                'entity_type' => 'events'
            ]
        );

        wordpressSyncerJob::dispatch();
        Alert::success('Success', 'Event has been updated!');

        return redirect()->route('event');


    }



    public function destroy($id)
    {
        try {
            $deleted_event = event::findOrFail($id);

            $deleted_event->delete();
            wordpressSyncerJob::dispatch();
            Alert::success('Success', 'Event has been deleted !');
            return redirect()->route('event');
        } catch (Exception $ex) {
            Alert::warning('Error', 'Cant deleted, Event already used !');
            return redirect()->route('event');
        }
    }


}
