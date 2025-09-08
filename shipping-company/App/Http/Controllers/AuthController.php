<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class AuthController extends Controller
{
    function index(){
        return "auth index";
    }

    function register(Request $request){
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|string|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'string|max:20',
            'role' => 'required|in:admin,client,driver,employee,manager',
            'password_confirmation' => 'required|min:6|string',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        $user = User :: create($validated);
        Auth :: login($user);
        return response() -> json(['message' => 'User registered successfully', 'user' => $user], 201);

    }

    function login(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth :: attempt($validated)) {
            $request -> session() -> regenerate();
            return response() -> json(['message' => 'User logged in successfully'], 200);
        }
    }

    function logout(Request $request){
        Auth :: logout();
        $request -> session() -> invalidate();
        $request -> session() -> regenerateToken();
        return response() -> json(['message' => 'User logged out successfully'], 200);
    }
}