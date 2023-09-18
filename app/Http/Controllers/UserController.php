<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //Show Register form
    public function create(){
        return view('users.register');
    }

    //Create and store user
    public function store(Request $request){
        $formFields = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|min:6|confirmed'
        ]);

        //Hash password
        $formFields['password'] = bcrypt($formFields['password']);

        //create user
        $user = User::create($formFields);

        //log in
        auth()->login($user);

        return redirect('/')->with('message', 'User created and logged in successfully!');
    }

    //Logout user
    public function logout(Request $request){
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'User logged out!');
    }

    //Show Login form
    public function login(){
        return view('users.login');
    }

    //Authenticate user
    public function authenticate(Request $request){
        $formFields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if(auth()->attempt($formFields)){
            $request->session()->regenerate();

            return redirect('/')->with('message', 'User logged in successfully');
        }

        return back()->withErrors(['email'=>'Invalid credentials'])->onlyInput('email');
    }

    //Show profile page
    public function profile(){
        return view('users.profile');
    }

    //Show Edit Profile form
    public function editProfile(User $user){
        return view('users.edit-profile', ['user'=>$user]);
    }

    //Submit to update user profile
    public function update(Request $request){
        $user = auth()->user();

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return redirect('/')->with('message', 'User profile updated successfully!');
    }

    //Show change password form
    public function changePassword(){
        return view('users.change-password');
    }

    //Submit to update password
    public function updatePassword(Request $request){
        $request->validate([
            'current_password'=>'required',
            'new_password'=>'required|min:6'
        ]);

        $user = Auth::user();

        if(!Hash::check($request->current_password, $user->password)){
            return back()->with('message', 'Current password is incorrect!');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect('/')->with('message', 'Password changed successfully!');
    }
}
