<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ChurnController extends Controller
{
    public function index()
    {
        $customers = DB::table('customers')->get();

        $csvFilePath = base_path('customers.csv');
        $file = fopen($csvFilePath, 'w');
        fputcsv($file, ['id', 'name', 'recency', 'frequency', 'monetary']);
        
        foreach ($customers as $customer) {
            fputcsv($file, [$customer->id, $customer->name, $customer->recency, $customer->frequency, $customer->monetary]);
        }
        fclose($file);

        $rScript = base_path('churn_model.R');
        $output = shell_exec("Rscript \"$rScript\" \"$csvFilePath\" 2>&1");

        $jsonFilePath = base_path('churn_results.json');
        if (File::exists($jsonFilePath)) {
            $jsonData = File::get($jsonFilePath);
            $processedData = json_decode($jsonData, true);
        } else {
            $processedData = [];
        }

        return view('dashboard', ['customers' => $processedData, 'r_error' => $output]);
    }
}