<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class OTPMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Validate the request for phone number and OTP code
        $request->validate([
            'phone_number' => 'required|string|regex:/^[0-9]{10,15}$/',
            'otp_code' => 'required|digits:4',
        ]);

        // Find the user with the provided phone number
        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        // Check if the OTP is correct and not expired
        if ($user->otp_code !== $request->otp_code || Carbon::now()->greaterThan($user->expire_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 401);
        }

        // OTP is valid, mark the phone number as verified
        $user->update([
            'phone_number_verified_at' => Carbon::now(),
            'otp_code' => null,
            'expire_at' => null,
        ]);

        return $next($request);
    }
}
