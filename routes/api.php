<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvitationController;

Route::get('/invitations', [InvitationController::class, 'index']);
Route::get('/invitations/{id}', [InvitationController::class, 'show']);
Route::post('/invitations', [InvitationController::class, 'store']);
Route::put('/invitations/{id}', [InvitationController::class, 'update']);
Route::delete('/invitations/{id}', [InvitationController::class, 'destroy']);
