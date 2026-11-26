<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if ($user === null || ! Hash::check($request->validated('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Ces identifiants ne correspondent à aucun compte.',
            ]);
        }

        return response()->json([
            'token' => $user->createToken('api')->plainTextToken,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function logout(Request $request): Response
    {
        $token = $request->user()->currentAccessToken();

        abort_unless($token instanceof PersonalAccessToken, 401);

        $token->delete();

        return response()->noContent();
    }
}
