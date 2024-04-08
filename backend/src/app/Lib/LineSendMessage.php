<?php

namespace App\Lib;

class LineSendMessage
{
    const NO_REPLY_KEYWORD = [
        '> 使い方',
    ];
    public static function exec(string $message, string $line_user_id)
    {
        if (in_array($message, self::NO_REPLY_KEYWORD)) {
            return;
        }
        $url = 'https://api.line.me/v2/bot/message/push';
        $body = [
            'to' => $line_user_id,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $message
                ]
            ]
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . env('LINE_ACCESS_TOKEN'),
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}