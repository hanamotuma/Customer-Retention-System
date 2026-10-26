<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChurnController;

// We create an API route to fetch the data
Route::get('/churn-data', [ChurnController::class, 'apiIndex']);