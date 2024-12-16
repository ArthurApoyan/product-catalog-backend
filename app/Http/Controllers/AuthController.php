<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
            ], [
                'first_name.required' => 'The first name field is required.',
                'last_name.required' => 'The last name field is required.',
                'email.required' => 'The email field is required.',
                'email.unique' => 'This email is already taken.',
                'password.min' => 'The password must be at least 8 characters long.',
            ]);

            $user = User::query()->create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json($user);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!auth()->attempt($credentials)) {
                return response()->json(['message' => 'Invalid email or password.'], 401);
            }

            $token = auth()->user()->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out']);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public function user(): ?Authenticatable
    {
        return auth()->user();
    }
}

