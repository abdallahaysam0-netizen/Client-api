<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AttachmentController;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogoutController;

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [LogoutController::class, 'logout']);
    Route::get('notifications', [DashboardController::class, 'getNotifications']);
    Route::post('notifications/mark-as-read', [DashboardController::class, 'markNotificationsRead']);
});

Route::resource('clients', ClientController::class);
Route::resource('notes', NoteController::class);
Route::resource('attachments', AttachmentController::class);
Route::get('dashboard/stats', [DashboardController::class, 'index']);
Route::get('activities', [DashboardController::class, 'activities']);
Route::get('attachments/{id}/download', [AttachmentController::class, 'download'])->name('attachments.download');
Route::get('attachments/{id}/base64',   [AttachmentController::class, 'base64'])->name('attachments.base64');
