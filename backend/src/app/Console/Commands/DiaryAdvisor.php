<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DiaryAdvisor extends Command
{
    protected $signature = 'diary:advise';
    protected $description = 'Get advice on a diary entry from OpenAI';


    public function handle()
    {
        $openaiapikey = $openaiapikey = env('OPENAI_API_KEY', '');

        $url = "https://api.openai.com/v1/chat/completions";
        
        $content ="新しいプロジェクトの提案を上司にメールで送信。少し緊張したが、前向きなフィードバックをもらえて安心した。";
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

        // Check if the cURL request was successful
        if ($response === false) {
            $this->error('Failed to make a request to the OpenAI API.');
            return;
        }

        // Decode the response
        $result = json_decode($response, true);

        // Check if 'choices' key exists in the response
        if (!isset($result['choices'])) {
            // Optionally, check if there are any error messages provided by the API
            if (isset($result['error'])) {
                $this->error('Error from OpenAI API: ' . $result['error']['message']);
            } else {
                $this->error('Unexpected response format from OpenAI API.');
            }
            return;
        }

        $result_message = $result["choices"][0]["message"]["content"];

        // Output the result
        $this->info($result_message);

    }
}
