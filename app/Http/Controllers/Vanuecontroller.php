<?php

namespace App\Http\Controllers;

use App\Models\country;
use App\Models\state;
use Illuminate\Http\Request;
use App\Models\Vanue;
use Exception;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\wordpress_reference;
use App\Jobs\wordpressSyncerJob;
class Vanuecontroller extends Controller
{
    public function index()
    {

        $vanue = Vanue::orderby('id', 'desc')->paginate(10);

        return view('vanue.vanue', compact('vanue'));

    }

    public function create()
    {
        $countries = country::get();
        $states = state::get();

        return view('vanue.vanue-add', compact('countries', 'states'));
    }
    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => 'required|string',
            'vanue_description' => 'nullable',
            'address' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|numeric|digits_between:5,7',
            'phone' => 'required|numeric|digits_between:10,14',
            // 'website' => ['nullable', 'regex:/^(https:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'],
            'website' => 'nullable'
        ];

        $validatedData = $request->validate($rules);

        $status = $request->has('status') ? true : false;
        
        // Create the Venue
        Vanue::create([
            'name' => $validatedData['name'],
            'vanue_description' => $validatedData['vanue_description'] ?? ' ',
            'address' => $validatedData['address'],
            'city' => $validatedData['city'],
            'country' => $validatedData['country'],
            'state' => $validatedData['state'],
            'postal_code' => $validatedData['postal_code'],
            'phone' => $validatedData['phone'],
            'website' => $validatedData['website'],
            'status' => $status,
        ]);

        wordpressSyncerJob::dispatch();
        // Display success message
        Alert::success('Success', 'Venue has been saved!');
        return redirect()->route('vanue');
    }

    public function edit($id)
    {
        $countries = country::get();
        $states = state::get();

        $vanue = Vanue::findOrFail($id);

        return view('vanue.vanue-edit', compact('vanue', 'countries', 'states'));

    }


    public function update(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => 'required|string',
            'vanue_description' => 'nullable',
            'address' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|numeric|digits_between:5,7',
            'phone' => 'required|numeric|digits_between:10,14',
        //    'website' => ['nullable', 'regex:/^(https:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'],
           'website' => 'nullable'

        ];


       
    
        $validatedData = $request->validate($rules);


        $status = $request->has('status') ? true : false;

        // Update the Venue
        Vanue::where('id', $request->id)->update([
            'name' => $validatedData['name'],
            'vanue_description' => $validatedData['vanue_description'] ?? ' ',
            'address' => $validatedData['address'],
            'city' => $validatedData['city'],
            'country' => $validatedData['country'],
            'state' => $validatedData['state'],
            'postal_code' => $validatedData['postal_code'],
            'phone' => $validatedData['phone'],
            'website' => $validatedData['website'],
            'status' => $status,
        ]);

        // Display success message

        wordpress_reference::updateWithoutTimestamp(
            [
                'status' => 1,
                'no_of_tries' => 1,
            ],
            [
                'ref_id' => $request->id,
                'entity_type' => 'venue'
            ]
        );
        
        wordpressSyncerJob::dispatch();
        Alert::success('Success', 'Venue has been updated!');
        return redirect()->route('vanue');
    }


    public function destroy($id)
    {
        try {
            $delete_vanue = Vanue::findOrFail($id);

            $delete_vanue->delete();
            wordpressSyncerJob::dispatch();
            Alert::success('Success', 'Venue has been deleted !');
            return redirect()->route('vanue');
        } catch (Exception $ex) {
            Alert::warning('Error', 'Cant delete, Venue already used !');
            return redirect()->route('vanue');
        }
    }
}
