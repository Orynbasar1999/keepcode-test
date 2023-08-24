<?php

namespace App\Http\Controllers;

use App\Services\UserCreator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function response;

class UserController extends Controller
{
    public function __construct(private readonly UserCreator $userCreator)
    {
    }

    public function login(Request $request)
    {
        $email = $request->post('email');
        $password = $request->post('password');

        $token = auth()->attempt(['email' => $email, 'password' => $password]);

        if (!$token) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'token' => $request->user()->createToken('api token')->plainTextToken,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        return ['success' => $this->userCreator->create($request)];
    }
}
