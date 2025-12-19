<?php

namespace App\Repositories\Auth\V1;

use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
    public function register(array $data);
    public function login(array $data);
    public function logout(Request $request);
}
