<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Iluminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();

        $user = User::create([
            'name' => $fields['name'], 'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $response = [
            'user' => $user,
            'token' => $user->createToken('token_user_' . $user->id)->plainTextToken
        ];

        $fields = $user = null;

        return response($response, 201);
    }

    public function login(LoginRequest $request)
    {
        // return $request;
        // return $request->all();
        $fields = $request->validated();
        // $fields = $request->all();
        //get user by EMAIL
        $user = User::where('email', $fields['email'])->first();

        if (!Hash::check($fields['password'], $user->password)) {
            return response(
                [
                    'message' => 'Incorrect password.',
                    'errors' => [
                        'password' => 'The password was not foud.'
                    ]
                ]
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
