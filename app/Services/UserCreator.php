<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserCreator
{
    public function create(Request $request): bool
    {
        $user = User::where(['email' => $request->get('email')])->first();

        if ($user) {
            return $user;
        }

        $user = new User(
            [
                'password' => Hash::make($request->get('password')),
                'name' => $request->get('name'),
                'email' => $request->get('email'),
            ]
        );

        return $user->save();
    }
}