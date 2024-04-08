<?php
namespace App\Repository;

class JournalRepository
{
    public static function fetchDiary($user_id,$today): array
    {
        $rows = \DB::select('SELECT content, type FROM diaries WHERE user_id = ? AND send_date = ?', [$user_id,$today]);
        return $rows;
    }
    public static function selectDiary($user_id,$today): array
    {
        $rows = \DB::select('SELECT content FROM diaries WHERE user_id = ? AND send_date = ? AND type = 0', [$user_id,$today]);
        return $rows;
    }

    public static function fetchFeedback($user_id,$today): array
    {
        $rows = \DB::select('SELECT feedback FROM diaries WHERE user_id = ? AND send_date = ?', [$user_id,$today]);
        return $rows;
    }


    public static function InsertDiary($user_id,$today,$content,$type)
    {
        try {
            // まずは、指定した日付でレコードが存在するか確認する
            $existingRows = \DB::select("SELECT * FROM diaries WHERE user_id = ? AND send_date = ? AND type = ?", [$user_id,$today,$type]);

            if (empty($existingRows)) {
                // レコードが存在しない場合、新しいレコードを挿入
                \DB::insert("INSERT INTO diaries (created_at, updated_at, user_id, content, type, send_date) VALUES (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?)", [$user_id,$content,$type,$today]);
                return ['status' => 'success', 'message' => 'Diary entry inserted.'];
            } else {
                // 既存のレコードを更新
                \DB::update("UPDATE diaries SET content = ? WHERE user_id = ? AND send_date = ? AND type = ?", [$content, $user_id, $today, $type]);
                return ['status' => 'success', 'message' => 'Diary entry updated.'];
            }
        } catch (\Exception $e) {
            // 例外が発生した場合の処理
            return ['status' => 'error', 'message' => 'An error occurred.'];
        }
    }
    public static function InsertFeedback($user_id,$today,$feedback)
    {
        try{
            \DB::update("UPDATE diaries SET feedback = ? WHERE user_id = ? AND send_date = ? AND type = 0 ", [$feedback,$user_id, $today]);
            return ['status' => 'success', 'message' => 'Diary entry updated.'];
                
            }
         catch (\Exception $e) {
            // 例外が発生した場合の処理
            return ['status' => 'error', 'message' => 'An add feedback error occurred.'];
        }
                    
    }   
    

}
