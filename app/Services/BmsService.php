<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BmsService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function sendSms($msg, $to)
    {
        $response = $this->client->request('GET', config('bms.api_url') . 'SendSMS.aspx', [
            'query' => [
                'user_name' => config('bms.user_name'),
                'password' => config('bms.password'),
                'msg' => trim($msg),
                'sender' => config('bms.sender'),
                'to' => $to,
            ],
        ]);
        return json_decode($response->getBody()->getContents());
    }
}