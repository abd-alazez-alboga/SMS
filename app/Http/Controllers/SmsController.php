<?php


namespace App\Http\Controllers;

use App\Services\BmsService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    protected $bmsService;

    public function __construct(BmsService $bmsService)
    {
        $this->bmsService = $bmsService;
    }


    public function sendSms(Request $request)
    {
        $request->validate([
            'msg' => 'required|string',
            'to' => 'required|string|regex:/^09[0-9]{8}$/',
        ]);

        $response = $this->bmsService->sendSms(
            $request->input('msg'),
            $request->input('to'),
        );

        return response()->json($response);
    }

    public function sendOTP(Request $request)
    {
        $otp = 3123321;
        $otpString = strval($otp);

        // $message = "Your OTP is " . $otpString;
        $message = "نحنا لعيبة خالو";

        $request->validate([
            'to' => 'required|string|regex:/^09[0-9]{8}$/',
        ]);


        $response = $this->bmsService->sendSms($message, $request->input('to'));

        return response()->json($response);
    }
}
