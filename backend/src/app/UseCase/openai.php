<?php

namespace App\Usecase;


class Openai
{
    public static function exec(string $content): string
    {
        
        $openaiapikey = $openaiapikey = env('OPENAI_API_KEY', '');
        $url = "https://api.openai.com/v1/chat/completions";
        
        // リクエストヘッダー
        $headers = array(
          'Content-Type: application/json',
          'Authorization: Bearer ' . $openaiapikey
        );
        
        // リクエストボディ
        // ここでは、「今日の日記」としてユーザーからの入力を模擬しています。
        // 「今日の日記」に対してAIがアドバイスや感想を提供するように設定します。
        $data = array(
          'model' => 'gpt-3.5-turbo',
          'messages' => [
              ["role" => "system", "content" => "日記のエントリーに対してアドバイスやフィードバックを提供してください。"],
              ['role' => 'user', 'content' => $content],
          ],
          'max_tokens' => 500,
        );
        
        // cURLを使用してAPIにリクエストを送信 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
        
        $response = curl_exec($ch); 
        curl_close($ch); 
        
        // 結果をデコード
        $result = json_decode($response, true);
        $result_message = $result["choices"][0]["message"]["content"];
        
        // 結果を出力 
        return $result_message; 
    }

    
}