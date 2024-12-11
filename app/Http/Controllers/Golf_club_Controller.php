<?php

namespace App\Http\Controllers;

use App\Models\golf_club;
use App\Models\golf_group;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Golf_club_Controller extends Controller
{
    public function index()
    {
        $golf_club = golf_club::with('golfGroup')->orderBy('username', 'desc')->paginate(10);
      
        return view('golf_club.golf-club', compact('golf_club'));
    }

    public function create()
    {
        $group_name = golf_group::where('status', true)->select('gname', 'id')->get();
    

        return view('golf_club.golf-club-add', compact('group_name'));
    }
    public function store(Request $request)
{
    // Define validation rules
    $rules = [
        'name' => 'required|string',
        'password' => 'required|string|min:8|max:20',  // Ensure password has a minimum length of 8
        'url' => 'required|url',  // Ensure the URL is valid
        'group_name' => 'required|string',
        'Club_Name' => 'required|string',
    ];

    // Define custom validation messages
    $messages = [
        'password.min' => 'Password must be at least :min characters.',  // Corrected message
    ];

    // Validate the incoming request
    $validatedData = $request->validate($rules, $messages);

    // Determine the status
    $status = $request->has('status_check') ? true : false;

    // Generate a salt
    $salt = Crypt::encryptString(Str::random(12));

    // Encrypt the password
    $encryptedPassword = Crypt::encryptString($validatedData['password']);

    // Create the Golf Club
    golf_club::create([
        'username' => $validatedData['name'],
        'password' => $encryptedPassword,
        'url' => $validatedData['url'],
        'group_name' => $validatedData['group_name'],
        'club_name' => $validatedData['Club_Name'],
        'salt' => $salt,
        'status' => $status
    ]);

    // Display success message
    Alert::success('Success', 'Golf Club has been saved!');

    // Redirect back
    return redirect('/golf-club');
}


    public function edits($id)
    {
        $golf_club = golf_club::findOrFail($id);
      
        $allGroupNames = golf_group::where('status', true)->select('gname', 'id')->get();
        return view('golf_club.golf-club-edit', compact('golf_club', 'allGroupNames'));
    }

    public function update(Request $request)
    {
        $rules = [     
            'name' => 'required|string',
            'url' => 'required|url',  
            'group_name' => 'required|string',
            'Club_Name' => 'required|string',
            'password' => 'nullable|string|min:8|max:20', 
        ];
    
        // Validate the incoming request
        $validatedData = $request->validate($rules);
    
        // Determine the status
        $status = $request->has('status_check') ? true : false;
    
        // Get the existing data
        $existingData = golf_club::find($request->id);
        $existingSalt = $existingData->salt;
        $existingPassword = $existingData->password;
    
        // Decrypt the existing password for comparison
  
    
        $password = $existingPassword;
        $salt = $existingSalt;
    
        // Check if the password needs to be updated
        if (!empty($validatedData['password'])) {
            $salt = Crypt::encryptString(Str::random(12));
            $password = Crypt::encryptString($validatedData['password']);
        }
    
        $updateData = [
            'username' => $validatedData['name'],
            'url' => $validatedData['url'],
            'group_name' => $validatedData['group_name'],
            'club_name' => $validatedData['Club_Name'],
            'salt' => $salt,
            'status' => $status,
            'password' => $password,  // Always update the password
        ];
    
        $updated = golf_club::where('id', $request->id)->update($updateData);
    
        // Display success message
        Alert::success('Success', 'Golf Club has been updated!');
        return redirect('/golf-club');
    }
    



    public function destroy($id)
    {
        try {
            $deleted_golf_club = golf_club::findOrFail($id);

            $deleted_golf_club->delete();

            Alert::success('Success', 'Golf Club has been deleted !');
            return redirect('/golf-club');
        } catch (Exception $ex) {
            Alert::warning('Error', 'Cant deleted, Golf Club already used !');
            return redirect('/golf-club');
        }
    }

}
