<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Iluminate\Http\Response;
use Iluminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request ){
        
        $fields = $request->validate([
            'name'=>'required|string',
            'email'=> 'required|string|unique:users,email',
            'password'=>'required|string|confirmed'
        ]);

        $user = User::create([
            'name'=>$fields['name'], 'email'=>$fields['email'],
            'password'=> bcrypt($fields['password']),  
        ]);

        $response = ['user'=>$user, 
        'token'=> $user->createToken('myapptoken')->plainTextToken];

        return response($response, 201);
    }

    public function login(Request $request ){
        
        $fields = $request->validate([
            'email'=> 'required|string|unique:users,email',
            'password'=>'required|string|confirmed'
        ]);

        //CHECK EMAIL
        $user = User::where('email', $fields['email'])->get();

        if($user->count()>1){ return response(
            ['message'=>'There is an inconsistency with your email, please contact support.'],401);
             
        }

        

        $response = ['user'=>$user, 
        'token'=> $user->createToken('myapptoken')->plainTextToken];

        return response($response, 201);
    }

    public function logout(Request $request ){
       
       $request->user()->tokens()->delete();
    }
}
