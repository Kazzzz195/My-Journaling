<?php

namespace App\Repository\trx;


class LineMessageRepository
{
    // public static function createMessages(string $content, int $type, string $send_date, string $line_user_id): int
    // {
    //     \DB::insert("INSERT INTO trx_line_messages (
    //     user_id,
    //     content,
    //     type,
    //     send_date
    // ) 
    // SELECT 
    //     id,
    //     ?,
    //     ?,
    //     ?
    // FROM trx_users 
    // WHERE line_user_id = ?
    // ", [$content, $type, $send_date, $line_user_id]);


    //     return \DB::getPdo()->lastInsertId();
    // }

    // //trx_users テーブルから指定した line_user_id の id を取得するメソッド
    // public static function selectUserId(string $line_user_id): array
    // {
    //     $rows = \DB::select("SELECT id FROM trx_users WHERE line_user_id = ?", [$line_user_id]);

    //     return $rows;
    // }

    // public static function getMessage(string $line_user_id): array
    // {
    //     date_default_timezone_set('Asia/Tokyo');
    //     $today = date("Y-m-d");
    //     $rows = \DB::select("SELECT content FROM trx_line_messages WHERE line_user_id = ? and send_date = ? ", [$line_user_id, $today]);

    //     return $rows;
    // }


    // public static function updateIsSetMessage(array $ids): void
    // {
    //     $rows = \DB::update("UPDATE trx_line_messages SET is_set_calendar = 1 WHERE id IN (" . implode(',', array_fill(0, count($ids), '?')) . ")", $ids);
    // }


    //その日すでにジャーナルを書いたか判定
    public static function existMessagesByDate($today, $userId): bool
    {
        $rows = \DB::select('SELECT content, type FROM diaries WHERE user_id = ? AND send_date = ?', [$userId,$today]);
        
        if(count($rows) === 0){
            return false;
        } else {
            return true;
        }
    }
    // public static function existMessagesByDate($message_date, $line_user_id): bool
    // {
    //     $rows = \DB::select("SELECT lm.id 
    //     FROM trx_line_messages AS lm 
    //     INNER JOIN trx_users AS u 
    //     ON lm.user_id = u.id
    //     WHERE lm.send_date = ? 
    //     AND u.line_user_id = ? ", [$message_date, $line_user_id]);

    //     if(count($rows) === 0){
    //         return false;
    //     } else {
    //         return true;
    //     }
    // }
}
