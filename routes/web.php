<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObjectDetectionController;

Route::get('/', [ObjectDetectionController::class, 'index']);
Route::post('/upload', [ObjectDetectionController::class, 'upload'])->name('upload');