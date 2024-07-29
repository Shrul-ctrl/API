<?php

use App\Http\Controllers\Api\LigaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('liga',[LigaController::class, 'index']);
Route::post('liga',[LigaController::class, 'store']);
Route::get('liga/{id}',[LigaController::class, 'show']);
Route::put('liga/{id}',[LigaController::class, 'update']);
Route::delete('liga/{id}',[LigaController::class, 'destroy']);

