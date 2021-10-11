<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
    //

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            dd("pass");
            return redirect()->intended('/');
        }
        else{
            return back()->withErrors(['message' => 'Username & password does not match. Please try again.']);
        }
    }
    public function getUser(Request $request)
    {
            return response()->json(['isSuccess' =>true,'session'=>$request->session()->all(),'user' =>Auth::user()->profile()]);
        
    }

    
}
