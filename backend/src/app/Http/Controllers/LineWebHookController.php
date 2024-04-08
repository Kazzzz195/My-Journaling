<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\trx\UserRepository;
use App\Repository\trx\LineMessageRepository;
use App\Usecase\SetRichMenu;
use App\Usecase\InsertCalendar;
use App\Lib\LineSendMessage;
use App\Lib\Log;
use App\Enum\LineEventType;

class LineWebhookController extends Controller
{
    public function post(Request $request)
    {
        // JSONデータを取得
        $json_string = file_get_contents('php://input');
        $webhook_request = json_decode($json_string);

        // LINEからのWebhookイベント
        foreach ($webhook_request->events as $event) {
            $event_type = $event->type;

            $line_user_id = $event->source->userId;
            $timestamp = $event->timestamp;

            // イベントのタイプに応じて処理を分岐
            switch ($event_type) {
                case LineEventType::MESSAGE:
                    $text = $event->message->text;
                    $date = date("Y-m-d", $timestamp / 1000);
                    Log::write($line_user_id, 'line_user_id.log');
                    $exist_today_messages = LineMessageRepository::existMessagesByDate($date, $line_user_id);
                    if (!$exist_today_messages) {

                        // 改行が2つ以上連続する箇所でメッセージを分割して配列に格納
                        $message_list = preg_split("/\n{2,}/", $text);
                        if (count($message_list) !== 3) {
                            $err_message = "正しい入力ではありません。以下のフォーマットで入力してください。\n\n";
                            $err_message .= "------\n";
                            $err_message .= "過去の自分\n\n";
                            $err_message .= "今日の自分\n\n";
                            $err_message .= "未来の自分\n";
                            $err_message .= "------";
                            LineSendMessage::exec($err_message, $line_user_id);
                            return;
                        }

                        try {
                            UserRepository::createUsers($line_user_id);
                        } catch (\Exception $e) {
                            // todo UNIQUE制約エラーの場合の処理。一旦握りつぶす
                            Log::write($e->getMessage(), 'exception.log');
                        }

                        $log = "Message - line_user_id: {$line_user_id}, Timestamp: {$timestamp}, Text: {$text}" . PHP_EOL;
                        Log::write($log, 'line_message.log');

                        // メッセージを処理
                        $description = "";
                        $message_ids = [];
                        foreach ($message_list as $key => $message) {
                            switch ($key) {
                                case 0:
                                    $description = $description . "【過去の自分へ】：" . "$message\n\n";
                                    $message_type = $key + 1; // 過去か今か未来かを識別する番号
                                    break;
                                case 1:
                                    $description = $description . "【今の自分へ】：" . "$message\n\n";
                                    $message_type = $key + 1; // 過去か今か未来かを識別する番号
                                    break;
                                case 2:
                                    $description = $description . "【未来の自分へ】：" . "$message\n\n";
                                    $message_type = $key + 1; // 過去か今か未来かを識別する番号
                                    break;
                            }
                            $log = "message_type: {$message_type}, message: {$message}" . PHP_EOL;
                            Log::write($log, 'debug.log');

                            $last_insert_id = LineMessageRepository::createMessages($message, $message_type, $date, $line_user_id);
                            $message_ids[] = $last_insert_id;
                        }

                        // $description → ユーザーが入力した文字が連結された状態で入っている
                        // ここでカレンダーに予定を追加する
                        InsertCalendar::exec($description, $line_user_id);
                        // trx_line_messages.is_set_calenderを1に更新する
                        LineMessageRepository::updateIsSetMessage($message_ids);
                        LineSendMessage::exec('journalの送信完了しました。', $line_user_id);

                        break;
                    } else {
                        // ラインメッセージ送信
                        LineSendMessage::exec('journalは1日1回までです。', $line_user_id);
                    }

                case LineEventType::FOLLOW:
                    // フォローイベントをログに記録
                    $log = "Follow - line_user_id: {$line_user_id}, Timestamp: {$timestamp}" . PHP_EOL;
                    Log::write($log, 'line_follow.log');

                    // リッチメニューの設定（初期）
                    $exist_google_refresh_token = UserRepository::existRefreshTokenByLineUserId($line_user_id);
                    if (!$exist_google_refresh_token) {
                        SetRichMenu::first($line_user_id);
                    } else {
                        SetRichMenu::second($line_user_id);
                    }

                    break;

                default:
                    // その他のイベントに関する処理は省略
                    break;
            }
        }
    }
}
//ユーザーが送信したメッセージの日付と同じ日付のメッセージがあるかのバリデーション
//その時にラインで返信できる機能を作成できるか
