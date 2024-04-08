<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;
use App\Repository\JournalRepository;
use App\Repository\TaskRepository;
use App\Usecase\Openai;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index($date)
    {    
        
        $user_id = Auth::id();
        $diary= JournalRepository::fetchDiary($user_id,$date);
        return $diary;

    }
    
    public function getTask($date)
    {
        $user_id = Auth::id();
        $tasks= TaskRepository::fetchTask($user_id,$date);
        return $tasks;
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'date' => 'required|date',
            'title' => 'required|string',
        ]);

        // Extract the date and content from the request
        $date = $request->input('date');
        $title = $request->input('title');
        $user_id = Auth::id();
        
        $new_tasks = TaskRepository::insertTask($user_id,$date,$title);
        
        
        // Return the response
        return response()->json($new_tasks,200);
    }


    /**
     * Display the specified resource.
     */
    public function show(Diary $diary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diary $diary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diary $diary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($task_id)
    {
        $user_id = Auth::id();
        $delete_task = TaskRepository::deleteTask($user_id,$task_id);
        
        return response()->json($delete_task,200);
    }
}
