<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AttachmentController;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogoutController;

use Illuminate\Support\Facades\Broadcast;

Route::post('login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    // Reverb/Broadcasting Auth
    Broadcast::routes(); 
    
    Route::post('logout', [LogoutController::class, 'logout']);

    Route::get('notifications', [DashboardController::class, 'getNotifications']);
    Route::post('notifications/mark-as-read', [DashboardController::class, 'markNotificationsRead']);
    Route::get('system/health', [DashboardController::class, 'health']); // Fixed 404 Health

    // Admin & Manager can view stats/activities (view permissions)
    Route::get('dashboard/stats', [DashboardController::class, 'index'])->middleware('permission:view-clients');
    Route::get('activities', [DashboardController::class, 'activities'])->middleware('permission:view-activity-logs');

    // Clients
    Route::get('clients', [ClientController::class, 'index'])->middleware('permission:view-clients');
    Route::get('clients/{client}', [ClientController::class, 'show'])->middleware('permission:view-clients');
    Route::post('clients', [ClientController::class, 'store'])->middleware('permission:create-clients');
    Route::put('clients/{client}', [ClientController::class, 'update'])->middleware('permission:edit-clients');
    Route::delete('clients/{client}', [ClientController::class, 'destroy'])->middleware('permission:delete-clients');

    // Notes
    Route::get('notes', [NoteController::class, 'index'])->middleware('permission:view-notes');
    Route::post('notes', [NoteController::class, 'store'])->middleware('permission:create-notes');
    Route::put('notes/{note}', [NoteController::class, 'update'])->middleware('permission:edit-notes');
    Route::delete('notes/{note}', [NoteController::class, 'destroy'])->middleware('permission:delete-notes');

    // Attachments
    Route::get('attachments', [AttachmentController::class, 'index'])->middleware('permission:view-attachments');
    Route::post('attachments', [AttachmentController::class, 'store'])->middleware('permission:create-attachments');
    Route::put('attachments/{attachment}', [AttachmentController::class, 'update'])->middleware('permission:edit-attachments');
    Route::delete('attachments/{attachment}', [AttachmentController::class, 'destroy'])->middleware('permission:delete-attachments');
    Route::get('attachments/{id}/download', [AttachmentController::class, 'download'])->middleware('permission:view-attachments');
    Route::get('attachments/{id}/base64', [AttachmentController::class, 'base64'])->middleware('permission:view-attachments');
});
