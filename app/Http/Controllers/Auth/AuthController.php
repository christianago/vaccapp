<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{  

    public function login()
    {
        return view('auth.login');
    }


    public function process_login(Request $request)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];
        $validator = Validator::make($request->except(['_token']), $rules);
        if ( $validator->fails() ){
            return Redirect::to('login')->withErrors($validator);
        }

        $username = $request->input('username');
        $password = $request->input('password');

        $credentials = [
            'username' => $username,
            'password' => $password,
        ];
        #dd($credentials);
        if ( Auth::attempt($credentials) ){
            return redirect()->route('home');
        } else{
            $request->session()->flash('error_msg', 'Invalid username or password.');
            return redirect()->back();
        }
    }


    public function register()
    {
        return view('auth.register');
    }


    public function process_register(Request $request)
    {   
        $customMessages = [
            'unique' => 'The username already exists. Please pick another username.'
        ];

        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6'
        ], $customMessages);

        $username = trim($request->input('username'));

        $user = User::create([
            'username' => $username,
            'role' => 'customer',
            'password' => Hash::make($request->input('password')),
        ]);

        if ( !empty($user) ){
            $request->session()->flash('success_msg', 'Your account was successfully created. You can now login');
            return redirect()->route('login');
        }
        return redirect()->route('auth.register');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}