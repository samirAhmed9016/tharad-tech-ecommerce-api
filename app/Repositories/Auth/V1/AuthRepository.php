<?php

namespace App\Repositories\Auth\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
        if (User::where('email', $data['email'])->exists()) {
            throw new \Exception('Email already exists');
        }
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $token = $user->createToken('API Token')->plainTextToken;
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;  // Return null on any failure
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return true;
    }
}
