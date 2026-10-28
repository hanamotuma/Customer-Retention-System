<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChurnController;

Route::get('/churn-data', [ChurnController::class, 'apiIndex']);
Route::post('/upload-csv', [ChurnController::class, 'uploadCsv']);