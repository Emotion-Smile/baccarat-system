<?php

namespace App\Kravanh\Support\External\Movider;

use Exception;
use Illuminate\Support\Facades\Log;


class Movider
{

    /**
     * @throws Exception
     */
    public static function send(string $to, string $otpCode)
    {
        $curl = curl_init();

        $data = array(
            'api_key' => '1vFglGy8aFP6FdmmtaQTEBSqtL5',
            'api_secret' => 'SrWNwcbad3U7YJdJ8HodIpbRWrckssHu5CDWdeay',
            'text' => "Your Heng Mart confirm code: {$otpCode}. Valid for 2 minutes.",
            'to' => "{$to}",
            'from' => 'MOVIDEROTP'
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.movider.co/v1/sms',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'cache-control: no-cache'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::channel('telegram')->error('SMS error => ' . $err);
            throw new Exception($err);
        } else {
            info("SMS OTP sent to: {$to}");
            info($response);
        }
    }
}
