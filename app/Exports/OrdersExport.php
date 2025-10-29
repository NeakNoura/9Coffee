<?php

namespace App\Exports;

use App\Models\Product\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Fetch all orders with related product
        return Order::with('product')->get()->map(function($order){
            return [
                'Order ID'       => $order->id,
                'Customer Name'  => $order->first_name,
                'Product Name'   => $order->product->name ?? 'N/A',
                'Price'          => $order->price,
                'Status'         => $order->status,
                'Payment Method' => $order->payment_method ?? 'Cash',
                'Date'           => $order->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Order ID','Customer Name','Product Name','Price','Status','Payment Method','Date'];
    }
}
