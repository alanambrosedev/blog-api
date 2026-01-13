<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest; // <--- Import the new Request
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // 1. Validation is already done by LoginRequest.
        // We retrieve the validated data (email & password) safely.
        $credentials = $request->validated();

        // 2. Attempt to authenticate using the Session Guard
        if (Auth::attempt($credentials)) {
            // 3. Regenerate session to prevent "Session Fixation" attacks
            $request->session()->regenerate();

            // 4. Return the user.
            // NOTE: We do NOT return a 'token' string here. See explanation below.
            return response()->json(['user' => $request->user()]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}
