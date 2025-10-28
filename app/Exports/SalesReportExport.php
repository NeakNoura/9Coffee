<?php

namespace App\Exports;

use App\Models\Product\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class SalesReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(price) as total_sales'),
            DB::raw('COUNT(id) as total_orders')
        )
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Total Sales',
            'Total Orders',
        ];
    }
}
