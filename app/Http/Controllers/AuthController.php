<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use DAO\UserDAO;
use Iluminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $userDAO = new UserDAO();
        $result = $userDAO->create($request->validated());
        if(!$result){
            return ['type'=>'error', 'message'=>'User could not be created'];
        }

        $response = [
            'type'=>'success',
            'message'=>'User registered successfully',
            'user' => $userDAO->entity,
            'token' => $userDAO->token
        ];

        $result = $userDAO = null;

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
