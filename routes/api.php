<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChurnController;
use App\Http\Controllers\ExportController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/churn-data', [ChurnController::class, 'apiIndex']);
    Route::post('/upload-csv', [ChurnController::class, 'uploadCsv']);
    
    // EXPORT ROUTES
    Route::get('/export/excel', [ExportController::class, 'exportExcel']);
    Route::get('/export/pdf', [ExportController::class, 'exportPdf']);
});

Route::get('/churn-data', [ChurnController::class, 'apiIndex']);
Route::post('/upload-csv', [ChurnController::class, 'uploadCsv']);