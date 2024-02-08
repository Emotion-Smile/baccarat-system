<?php

namespace App\Kravanh\Domain\Integration\Http\Controllers\Api;

use App\Kravanh\Domain\Integration\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController
{
    public function __invoke(
        LoginRequest $request
    ): JsonResponse
    {
        $user = User::query()
            ->whereName($request->username)
            ->whereSuspend(false)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => [
                    'The provided credentials are incorrect.'
                ],
            ]);
        }

        $token = $user->createToken($user->name)->plainTextToken;

        return asJson(['token' => $token], 201);
    }
} 