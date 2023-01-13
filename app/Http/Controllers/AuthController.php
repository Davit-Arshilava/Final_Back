<?php

namespace App\Http\Controllers;


use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        return response([
            'message' => 'User registered successfully'
        ]);
    }

    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return response(['message' => 'invalid credentials']);
        }

        $token = auth()->user()->createToken('accesstoken')->plainTextToken;

        return response([
            'user' => auth()->user(),
            'token' => $token
        ]);

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'You are logged out'
        ]);
    }
}
