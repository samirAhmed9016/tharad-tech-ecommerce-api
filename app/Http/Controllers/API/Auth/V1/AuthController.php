<?php

namespace App\Http\Controllers\API\Auth\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\V1\AuthResource;
use App\Repositories\Auth\V1\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authRepo;

    public function __construct(AuthRepositoryInterface $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $result = $this->authRepo->register($data);

        //here i use object to conver from array to object for resource
        return new AuthResource((object)$result);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $result = $this->authRepo->login($data);

        if (!$result) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }

        return new AuthResource((object)$result);
    }
    // public function register(RegisterRequest $request)
    // {
    //     $data = $request->validated();
    //     return $this->authRepo->register($data);
    // }

    // public function login(LoginRequest $request)
    // {
    //     $data = $request->validated();
    //     $result = $this->authRepo->login($data);

    //     if (!$result) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     return $result;
    // }

    public function logout(Request $request)
    {
        return $this->authRepo->logout($request);
    }
}
