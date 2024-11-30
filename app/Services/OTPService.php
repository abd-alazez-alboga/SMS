<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OTPService
{
    protected $apiUrl;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->apiUrl = config('services.bms.api_url', env('BMS_API_URL'));
        $this->username = env('BMS_USER_NAME');
        $this->password = env('BMS_PASSWORD');
    }

    public function sendOTP($phoneNumber, $otpCode)
    {
        $message = "رمز التحقق الخاص بك هو {$otpCode}.\nيرجى استخدام هذا الرمز لإكمال عملية التحقق.\nلا تقم بمشاركة هذا الرمز مع أي شخص آخر.\nإذا لم تطلب هذا الرمز، يرجى تجاهل هذه الرسالة.";

        $response = Http::get($this->apiUrl, [
            'job_name' => 'OTP',
            'user_name' => $this->username,
            'password' => $this->password,
            'msg' => $message,
            'to' => $phoneNumber,
            'sender' => 'BABEL',
        ]);

        return $response->successful();
    }
}
