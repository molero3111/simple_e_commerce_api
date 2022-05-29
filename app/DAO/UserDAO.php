<?php

namespace DAO;

use App\Models\User;
use DAO\DAO;

class UserDAO extends DAO
{

    public string $token;
    public function create(array $fields): bool
    {
        $user =  User::create([
            'name' => $fields['name'], 'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $this->token = $user->createToken('token_user_' . $user->id)->plainTextToken;
        $this->entity = $user;
        $user = null;
        return true;
    }
}
