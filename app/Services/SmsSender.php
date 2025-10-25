<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsSender
{
    /**
     * Send an SMS to a given mobile number.
     * 
     * @param string $to   E.164 formatted number (+91XXXXXXXXXX)
     * @param string $text Message body
     */
    public function send(string $to, string $text): void
    {
        // 👉 Development mode (logs only)
        Log::info("SMS to {$to}: {$text}");

        // --- Example 1: MSG91 (India) ---
        // Http::withHeaders([
        //     'authkey' => config('services.msg91.authkey'),
        // ])->post('https://api.msg91.com/api/v5/flow/', [
        //     'template_id' => config('services.msg91.template_id'),
        //     'sender'      => config('services.msg91.sender'),
        //     'mobiles'     => ltrim($to, '+'),
        //     'VAR1'        => $text,
        // ])->throw();

        // --- Example 2: Exotel ---
        // $sid   = config('services.exotel.sid');
        // $token = config('services.exotel.token');
        // $from  = config('services.exotel.from');
        // Http::withBasicAuth($sid, $token)->asForm()->post(
        //     "https://api.exotel.com/v1/Accounts/{$sid}/Sms/send.json", [
        //         'From' => $from,
        //         'To'   => $to,
        //         'Body' => $text,
        //     ]
        // )->throw();

        // --- Example 3: Twilio (global) ---
        // $sid   = config('services.twilio.sid');
        // $token = config('services.twilio.token');
        // $from  = config('services.twilio.from');
        // Http::withBasicAuth($sid, $token)->asForm()->post(
        //     "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
        //         'From' => $from,
        //         'To'   => $to,
        //         'Body' => $text,
        //     ]
        // )->throw();
    }
}
