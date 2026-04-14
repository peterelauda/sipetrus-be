<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Resources\Authentication\LoginResource;
use App\Http\Resources\Authentication\UserResource;
use App\Services\Authentication\AuthService;
use App\Traits\ApiLogger;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiLogger, ApiResponser;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService
                ->login($request->email, $request->password);

            return $this->success('Login successfully', new LoginResource($data), 200);
        } catch (\Throwable $th) {
            $this->logError('Failed to login: ', $th);

            return $this->error('Failed to login', 500, $th->getMessage());
        }
    }

    public function me(Request $request)
    {
        return $this->success('User profile retrieved successfully', new UserResource($request->user()), 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Logged out', null, 200);
    }
}
