<?php

use App\Http\Controllers\DiaryController;
use App\Http\Controllers\LineWebhookController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/journal-date/{date}/',[DiaryController::class, 'index']);
Route::post('/journal-date/update',[DiaryController::class, 'store']);
Route::get('/journal-date/{date}/feedback',[DiaryController::class, 'getFeedback']);
Route::get('/journal-date/{date}/reflection',[DiaryController::class, 'selectFeedback']);
Route::get('/journal-date/{date}/task',[TaskController::class, 'getTask']);
Route::post('/journal-date/insert-task',[TaskController::class, 'store']);
Route::delete('/journal-date/delete-task/{taskId}',[TaskController::class, 'destroy']);

Route::post('/line_webhook', [LineWebhookController::class, 'post']);