<?php

namespace App\Http\Controllers\Api\Auth\V1;

use App\Enums\ResponseMethodEnum;
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
        try {
            $result = $this->authRepo->register($request->validated());

            return generalApiResponse(
                method: ResponseMethodEnum::SINGLE,
                resource: AuthResource::class,
                dataPassed: $result['user'],
                customMessage: 'User registered successfully',
                customStatusMsg: 'success',
                customStatus: 201,
                additionalData: [
                    'token' => $result['token'],
                    'token_type' => 'Bearer'
                ]
            );
        } catch (\Exception $e) {
            // Handle duplicate email specifically
            if (str_contains($e->getMessage(), 'already exists')) {
                return generalApiResponse(
                    method: ResponseMethodEnum::CUSTOM,
                    dataPassed: null,
                    customMessage: $e->getMessage(),
                    customStatusMsg: 'error',
                    customStatus: 409  // Conflict
                );
            }

            // Handle other errors
            return generalApiResponse(
                method: ResponseMethodEnum::CUSTOM,
                dataPassed: null,
                customMessage: 'Registration failed',
                customStatusMsg: 'error',
                customStatus: 500
            );
        }
    }

    /**
     * Login with simple error handling
     */
    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authRepo->login($request->validated());

            if (!$result) {
                return generalApiResponse(
                    method: ResponseMethodEnum::CUSTOM,
                    dataPassed: null,
                    customMessage: 'Invalid email or password',
                    customStatusMsg: 'error',
                    customStatus: 401
                );
            }

            return generalApiResponse(
                method: ResponseMethodEnum::SINGLE,
                resource: AuthResource::class,
                dataPassed: $result['user'],
                customMessage: 'User logged in successfully',
                customStatusMsg: 'success',
                customStatus: 200,
                additionalData: [
                    'token' => $result['token'],
                    'token_type' => 'Bearer'
                ]
            );
        } catch (\Exception $e) {
            return generalApiResponse(
                method: ResponseMethodEnum::CUSTOM,
                dataPassed: null,
                customMessage: 'Login failed',
                customStatusMsg: 'error',
                customStatus: 500
            );
        }
    }

    /**
     * Logout with simple error handling
     */
    public function logout(Request $request)
    {
        try {
            $this->authRepo->logout($request);

            return generalApiResponse(
                method: ResponseMethodEnum::CUSTOM,
                dataPassed: null,
                customMessage: 'User logged out successfully',
                customStatusMsg: 'success',
                customStatus: 200
            );
        } catch (\Exception $e) {
            return generalApiResponse(
                method: ResponseMethodEnum::CUSTOM,
                dataPassed: null,
                customMessage: 'Logout failed',
                customStatusMsg: 'error',
                customStatus: 500
            );
        }
    }
}
