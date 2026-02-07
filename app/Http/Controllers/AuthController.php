<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                // throw ValidationException::withMessages([
                //     'email' => ['The provided credentials are incorrect.'],
                // ]);
                return response()->json([
                    'message' => 'Email atau password salah'
                ], 401);
            }
            $token = $user->createToken('user_login')->plainTextToken;
            return response()->json([
                'message' => 'login successful',
                'token' => $token,
                'user' => $user
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'login failed',
                'error' => $th->getMessage()
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'logged out successfully'
        ]);
    }
    public function checkApi()
    {
        try {
            return response()->json([
                'status' => 'true',
                'message' => 'API has ben connected',
                'server-time' => now()
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'false',
                'message' => 'API is not connected',
                'error' => $th->getMessage(),
            ]);
        }
    }
}
