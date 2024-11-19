<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\OTPService;

class OTPController extends Controller
{
    protected $otpService;

    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function sendOTP(Request $request)
    {
        // Validate phone number
        $request->validate([
            'phone_number' => 'required|string|regex:/^[0-9]{10,15}$/',
        ]);

        // Find the user
        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        // Generate OTP and set expiration
        $otpCode = rand(1000, 9999);
        $user->update([
            'otp_code' => $otpCode,
            'expire_at' => now()->addMinutes(15),
        ]);

        // Send the OTP via the OTPService
        $sent = $this->otpService->sendOTP($user->phone_number, $otpCode);

        if (!$sent) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
        ], 200);
    }
    public function verifyOTP(Request $request)
    {
        // Validate phone number and OTP code
        $request->validate([
            'phone_number' => 'required|string|regex:/^[0-9]{10,15}$/',
            'otp_code' => 'required|digits:4',
        ]);

        // Find the user
        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        // Check OTP validity
        if ($user->otp_code !== $request->otp_code || now()->greaterThan($user->expire_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 401);
        }

        // Mark phone number as verified
        $user->update([
            'phone_number_verified_at' => now(),
            'otp_code' => null,
            'expire_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Phone number verified successfully.',
        ], 200);
    }
}
