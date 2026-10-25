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

        $rScript = database_path('migrations/churn_model.R');
        $output = File::exists($rScript)
            ? shell_exec(sprintf(
                'cd %s && Rscript %s %s 2>&1',
                escapeshellarg(base_path()),
                escapeshellarg($rScript),
                escapeshellarg($csvFilePath)
            ))
            : null;

        $jsonFilePath = base_path('churn_results.json');
        $processedData = [];

        if (File::exists($jsonFilePath)) {
            $decoded = json_decode(File::get($jsonFilePath), true);
            if (is_array($decoded) && ! empty($decoded)) {
                $processedData = $decoded;
            }
        }

        if (empty($processedData)) {
            $processedData = $customers->map(function ($customer) {
                $score = (
                    ((float) $customer->recency / 365) * 0.5
                    + (1 - ((float) $customer->frequency / 50)) * 0.25
                    + (1 - ((float) $customer->monetary / 5000)) * 0.25
                ) * 100;

                $score = max(0, min(100, $score));

                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'recency' => $customer->recency,
                    'frequency' => $customer->frequency,
                    'monetary' => $customer->monetary,
                    'churn_probability' => round($score, 2),
                    'risk_label' => $score > 70 ? 'High Risk' : ($score > 40 ? 'Moderate Risk' : 'Low Risk'),
                ];
            })->values()->all();
        }

        return view('dashboard', [
            'customers' => $processedData,
            'r_error' => $output,
        ]);
    }
}