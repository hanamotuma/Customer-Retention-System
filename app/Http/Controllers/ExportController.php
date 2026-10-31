<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ExportController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new CustomersExport, 'customer_retention_data.xlsx');
    }

    public function exportPdf()
    {
        $customers = DB::table('customers')->get()->toArray();

        // Call the R API
        try {
            $response = Http::timeout(10)->post('http://127.0.0.1:8001/predict', $customers);
            if ($response->failed()) {
                return response()->json(['error' => 'R API failed'], 500);
            }
            $processedData = $response->json();
        } catch (\Exception $e) {
            return response()->json(['error' => 'R Server is not running. Start plumber.'], 500);
        }

        $pdf = Pdf::loadView('pdf_report', ['customers' => $processedData]);
        return $pdf->download('churn_risk_report.pdf');
    }
}