<?php
namespace App\Repository;

class TaskRepository
{
    

    public static function insertTask($user_id,$today,$title)
    {
        try {
        
                // レコードが存在しない場合、新しいレコードを挿入
            \DB::insert("INSERT INTO tasks (created_at, updated_at, user_id, title,due_date) VALUES (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?,?,?)", [$user_id,$title,$today]);
               
        } catch (\Exception $e) {
            // 例外が発生した場合の処理
            return ['status' => 'error', 'message' => 'An error occurred.'];
        }
    }

    public static function fetchTask($user_id,$today)
    {
        $row = \DB::select("SELECT  id, title FROM tasks WHERE user_id = ? AND due_date = ? ", [$user_id,$today]);
        return $row;
        
    }

    public static function deleteTask($user_id, $id)
    {
        $row = \DB::delete("DELETE FROM tasks WHERE id = ? AND user_id = ?", [$id, $user_id]);
        return $row;
       
    }

    public static function updateTask($user_id, $id)
    {
        \DB::update("UPDATE tasks SET completed = ? WHERE id = ? AND user_id = ?", [$id, $user_id]);
        return ['status' => 'success', 'message' => 'Diary entry updated.'];
       
    }
}
