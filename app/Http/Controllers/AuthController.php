<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Iluminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'], 'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $response = [
            'user' => $user,
            'token' => $user->createToken('token_user_' . $user->id)->plainTextToken
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {

        $fields = $request->validate([
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        //CHECK EMAIL
        $user = User::where('email', $fields['email'])->first();

        if (!$user) {
            return response(
                ['message' => 'Your E-Mail address was not found.'],
                401
            );
        }
        if (!Hash::check($fields['password'], $user->password)){
            return response(
                ['message' => 'Incorrect password.'],
                401
            );
        }



        $response = [
            'user' => $user,
            'token' => $user->createToken('user_token_' . $user->id)->plainTextToken
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();
        return ['type' => 'success', 'message' => 'Logged out succesfully'];
    }
}
