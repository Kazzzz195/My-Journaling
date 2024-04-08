<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;
use App\Repository\JournalRepository;
use App\Usecase\Openai;
use Illuminate\Support\Facades\Auth;

class DiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index($date)
    {    
        
        $userId = Auth::id();
        $diary= JournalRepository::fetchDiary($userId,$date);
        return $diary;

    }
    
    public function getFeedback($date)
    {
        $userId = Auth::id();
        $diary= JournalRepository::selectDiary($userId,$date);
        $contentString = implode("\n", array_column($diary, 'content'));
        $feedback = Openai::exec($contentString);
        JournalRepository::InsertFeedback($userId,$date,$feedback);

        return $feedback;
    }

    public function selectFeedback($date)
    {
        $userId = Auth::id();
        $diary= JournalRepository::fetchFeedback($userId,$date);
        return $diary;
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
            'content' => 'required|string',
            'id'=> 'required|integer'
        ]);

        // Extract the date and content from the request
        $date = $request->input('date');
        $content = $request->input('content');
        $id = $request->input('id');
        $userId = Auth::id();
        
        //get feedback from openai
        //$feedback = Openai::exec($content);


        // Insert the diary entry
        $newdiary = JournalRepository::InsertDiary($userId, $date, $content,$id);
        
        //insert feedback
        //JournalRepository::InsertFeedback($userId, $date, $feedback);
        
        
        // Return the response
        return response()->json($newdiary,200);
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
    public function destroy(Diary $diary)
    {
        //
    }
}
