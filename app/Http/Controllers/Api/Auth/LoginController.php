<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\LoginRequest;
use App\Model\Token\UseCases\Token\TokenService;
use App\Model\User\UseCases\Auth\Api\LoginService;
use Illuminate\Support\Facades\Cookie;

class LoginController
{
    private LoginService $loginService;
    private TokenService $tokenService;

    public function __construct(LoginService $loginService, TokenService $tokenService)
    {
        $this->loginService = $loginService;
        $this->tokenService = $tokenService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->loginService->getUserByCredentials(
                $request->input('email'),
                $request->input('password')
            );

            $token = $this->tokenService->createToken($user);

            return response()->json([
                'status' => 'success',
                'user' => $user->toArray(),
                'token' => $token->getToken(),
                'expired' => $token->getExpiredAt()->getTimestamp() * 1000
            ]);
        } catch (\DomainException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
