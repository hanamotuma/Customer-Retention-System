<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChurnController;

Route::get('/', [ChurnController::class, 'index']);