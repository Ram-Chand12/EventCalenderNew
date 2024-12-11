<?php

namespace App\Http\Controllers;


use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rule;
use App\Jobs\wordpressSyncerJob;
use Illuminate\Support\Facades\Crypt;
use App\Models\wordpress_reference;
use App\Models\golf_club;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {

        $user = User::orderby('id', 'desc')->where('user_type', 'user')->paginate(10);



        // dd($user_data_by_id);
        return view('Users.user', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Users.user-add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $email = $request->input('email');
        $userType = $request->input('user_type');

        // Example condition: Check if email is unique for the given user type
        if ($userType === 'user') {
            // Check if a normal user with this email already exists
            $existingUser = User::where('email', $email)->where('user_type', 'user')->first();
        }
        if ($existingUser) {
            // Handle case where a normal user with this email already exists
            // Alert::success('Error', 'A user with this email already exists.');
            return redirect()->back()->withInput()->with('error', 'A user with this email already exists.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'fname' => 'required|string',
            'lname' => 'required|string',
            // 'role' => 'required',
        ]);
        $status = $request->has('status') ? true : false;

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Crypt::encryptString($request->input('password')),
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'role' => "administrator",
            'status' => $status,
            'user_type' => $request->input('user_type'),
        ]);

        Alert::success('Success', 'User has been saved!');
        wordpressSyncerJob::dispatch();

        return redirect('/users');
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
        return view('Users.user-edit', compact('data'));
    }

   
    public function update(Request $request)
    {
        

        $email = $request->input('email');
        $userType = $request->input('user_type');
        $id = $request->input('id');

        
        if ($userType === 'user') {
            // Check if a normal user with this email already exists
            $existingUser = User::where('email', $email)
                ->where('user_type', 'user')
                ->where('id', '!=', $id) // Exclude current user ID
                ->first();
        }
        if ($existingUser) {
            
            return redirect()->back()->with('error', 'A user with this email already exists.');

        }



        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',                
            ],

            'fname' => 'required|string',
            'lname' => 'required|string',            
            'password' => 'nullable|min:8|max:20',
        ]);

        $status = $request->has('status_check') ? true : false;

        $userData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'role' => "administrator",
            'status' => $status,
            'user_type' => $request->input('user_type'),
        ];

        
        if ($request->filled('password')) {
            $userData['password'] = Crypt::encryptString($request->input('password'));
        }
    
        $user = User::where('id', $request->id)->first();

        if ($user) {
            $user->update($userData);
            $user->touch(); 
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
        return redirect('/users');
    }
}
