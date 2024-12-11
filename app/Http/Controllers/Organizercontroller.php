<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organizer;
use Exception;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\wordpress_reference;
use App\Jobs\wordpressSyncerJob;



class Organizercontroller extends Controller
{
    public function index(){

        $Organizer= Organizer::orderby('id','desc')->paginate(10);

        return view ('Organizer.Organizer',compact('Organizer'));

    }


public function create(){

return view ('Organizer.Organizer-add');

}

public function store(Request $request)
{
    // Validation rules
    $rules = [
        'name' => 'required|string|max:255',
        'description' => 'string',
        'phone' => 'required|numeric|digits_between:10,14',
        'website' => 'nullable',
        'email' => 'required|email',
        
    ];

    $validatedData = $request->validate($rules);

    $status = $request->has('status') ? true : false;

    // Create Organizer with validated data
    Organizer::create([
        'name' => $validatedData['name'],
        'description' => $validatedData['description'],
        'phone' => $validatedData['phone'],
        'website' => $validatedData['website'],
        'email' => $validatedData['email'],
        'status' => $status,
    ]);

    // Success message
    Alert::success('Success', 'Organizer has been saved !');
    wordpressSyncerJob::dispatch();

    // Redirect to organizer index page
    return redirect('/organizer');
}


public function edit($id){

   
    $Organizer= Organizer::findorfail($id);
    
    return view('Organizer.Organizer-edit',compact('Organizer')); 

}
public function update(Request $request)
{
 
    $rules = [
        'name' => 'required|string|max:255',
        'description' => 'string',
        'phone' => 'required|numeric|digits_between:10,14',
        'website' => 'nullable',
        'email' => 'required|email',
    ];

    $validatedData = $request->validate($rules);

    $status = $request->has('status') ? true : false;

    // Update Organizer with validated data
    Organizer::where('id', $request->id)->update([
        'name' => $validatedData['name'],
        'description' => $validatedData['description'],
        'phone' => $validatedData['phone'],
        'website' => $validatedData['website'],
        'email' => $validatedData['email'],
        'status' => $status,
    ]);

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
    Alert::success('Success', 'Organizer has been updated!');

    // Redirect to organizer index page
    return redirect('/organizer');
}


public function destroy($id)
{
    try {
        $Organizer = Organizer::findOrFail($id);

        $Organizer->delete();
        wordpressSyncerJob::dispatch();
        Alert::success('Success', 'Organizer has been deleted !');
        return redirect('/organizer');
    } catch (Exception $ex) {
        Alert::warning('Error', 'Cant deleted, Organizer already used !');
        return redirect('/organizer');
    }
}

}
