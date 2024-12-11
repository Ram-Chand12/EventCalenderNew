<?php

namespace App\Http\Controllers;
use Illuminate\Auth\Passwords;
use App\Mail\ForgetPassword;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{

    public function password(){
        return view('password');
    }

    public function index()
    {

        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(Request $request)
    {
        // dd(request()->all());
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            Alert::error('Error', 'User does not exist!');
            return redirect('/login');
        }
    
        
        if ($user->user_type == 'wordpress user') {
            Alert::error('Error', 'User does not exist!');
            return redirect('/login');
        }
        // $decryptedPassword=null;
        // if(!Hash::check($credentials['password'], $user->password)){

        
        $decryptedPassword = Crypt::decryptString($user->password);
    // }
// dd($decryptedPassword,$credentials['password']);
        if ($decryptedPassword === $credentials['password'] ) {
            if (Auth::loginUsingId($user->id)) {
                if ($user->status == true) {
                    $request->session()->regenerate();
                    return redirect()->intended('/dashboard');
                } else {
                    Auth::logout();
                    Alert::error('Error', 'Your account is inactive.');
                    return redirect('/login');
                }
            } else {
                Alert::error('Error', 'Login failed!');
                return redirect('/login');
            }
        } else {
            Alert::error('Error', 'Incorrect Password Login failed!');
            return redirect('/login');
        }
    }

    public function register()
    {
        return view('auth.register', [
            'title' => 'Register',
        ]);
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'passwordConfirm' => 'required|same:password'
        ]);

        $validated['password'] = Hash::make($request['password']);

        $user = User::create($validated);

        Alert::success('Success', 'Register user has been successfully !');
        return redirect('/login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
        Alert::success('Success', 'Log out success !');
        return redirect('/login');
    }

    public function forget_password_index(){
        return view('auth.resetpassword');
    }
  
    public function resetPassword(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Check if the email exists in the database
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => ['User does not exist']])->withInput();
        }

        // If validation fails, return back with errors
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Attempt to reset the password using Password facade
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Crypt::encryptString($password), // Ensure to hash the password properly
                    
                ])->save();
            }
        );

        // Check the status and redirect accordingly
        if ($status === Password::PASSWORD_RESET) {
            // Password reset successful
            return redirect()->route('login')->with('status', 'Password updated successfully');
        } else {
            // Password reset failed
            return back()->withErrors(['email' => [trans($status)]])->withInput();
        }
    }




}
