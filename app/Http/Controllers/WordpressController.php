<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rule;
use App\Jobs\wordpressSyncerJob;
use Illuminate\Support\Facades\Crypt;
use App\Models\wordpress_reference;
use App\Models\golf_club;
use Exception;

class WordpressController extends Controller
{
    public function index()
    {

        $user = User::orderby('id','desc')->where('user_type','wordpress user')->paginate(10);

      

        // dd($user_data_by_id);
        return view('wordpress_users.user', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('wordpress_users.user_add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $email = $request->input('email');
        $userType = $request->input('user_type');
    
        // Example condition: Check if email is unique for the given user type
        if ($userType === 'wordpress user') {
            // Check if a normal user with this email already exists
            $existingUser = User::where('email', $email)->where('user_type', 'wordpress user')->first();
        }
            if ($existingUser) {
                // Handle case where a normal user with this email already exists
                // Alert::error('Error', 'A user with this email already exists.');
                return redirect()->back()->withInput()->with('error','A user with this email already exists.');
               
            }

        // dd('dzf');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|max:20',
            'fname' => 'required|string',
            'lname' => 'required|string',
            'role' => 'required',
        ]);
        $status = $request->has('status_check') ? true : false;

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Crypt::encryptString($request->input('password')),
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'role' => $request->input('role'),                         //,
            'status' => $status,
            'user_type' => $request->input('user_type'),
        ]);

        Alert::success('Success', 'User has been saved!');
        wordpressSyncerJob::dispatch();

        return redirect('/wordpress-users');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $data)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = User::findOrFail($id);
        // dd($data);
        return view('wordpress_users.user_edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request)
    // {

    //     $request->validate([
    //         'id' => 'required|exists:users,id',
    //         'name' => 'required|string|max:255',
    //         'email' => [
    //             'required',
    //             'email',
    //             'max:255',
    //             Rule::unique('users')->ignore($request->id, 'id'),
    //         ],

    //         'fname' => 'required|string',
    //         'lname' => 'required|string',
    //         'role' => 'required',


    //         // Add other validation rules as necessary
    //     ]);

    //     $status = $request->has('status_check') ? true : false;

    //     User::where('id', $request->id)->update([
    //         'name' => $request->input('name'),
    //         'email' => $request->input('email'),
    //         'password' => bcrypt($request->input('password')),
    //         'fname' => $request->input('fname'),
    //         'lname' => $request->input('lname'),
    //         'role' => $request->input('role'),

    //         'status' => $status,
    //     ]);

    //     Alert::success('Success', 'User has been updated!');
    //     return redirect('/users');
    // }
    public function update(Request $request)
    {
         
        $email = $request->input('email');
        $userType = $request->input('user_type');
        $id = $request->input('id');
    
        // Example condition: Check if email is unique for the given user type
        if ($userType === 'wordpress user') {
            // Check if a normal user with this email already exists
            $existingUser = User::where('email', $email)
                            ->where('user_type', 'wordpress user')
                            ->where('id', '!=', $id) // Exclude current user ID
                            ->first();
        }
            if ($existingUser) {
                // Handle case where a normal user with this email already exists
                // Alert::error('Error', 'A user with this email already exists.');
                return redirect()->back()->with('error','A user with this email already exists.');
               
            }

        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                // Rule::unique('users')->ignore($request->id, 'id'),
            ],

            'fname' => 'required|string',
            'lname' => 'required|string',
            'role' => 'required',
            'password' => 'nullable|min:8|max:20',
        ]);

        $status = $request->has('status_check') ? true : false;

        $userData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'role' => $request->input('role'),
            'status' => $status,
            'user_type' => $request->input('user_type'),
        ];

        // Only update password if it's provided
        if ($request->filled('password')) {
            $userData['password'] = Crypt::encryptString($request->input('password'));
        }

        $user = User::where('id', $request->id)->first();

        if ($user) {
            $user->update($userData);
            $user->touch(); // This updates the 'updated_at' timestamp
        }
        wordpress_reference::updateWithoutTimestamp(
            [
                'status' => 1,
                'no_of_tries' => 1,
            ],
            [
                'ref_id' => $request->id,
                'entity_type' => 'user'
            ]
        );
            wordpressSyncerJob::dispatch();
        Alert::success('Success', 'User has been updated!');
        return redirect('/wordpress-users');
    }
}
