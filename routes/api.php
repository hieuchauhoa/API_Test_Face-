<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaceComparisonController;

Route::get('/face-comparison', [FaceComparisonController::class, 'compareFaces']);
