<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ensure the Customer model is correct
        return Customer::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Recency (Days)',
            'Frequency',
            'Monetary ($)',
        ];
    }
}