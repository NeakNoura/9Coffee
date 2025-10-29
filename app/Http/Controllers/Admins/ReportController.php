<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Exports\SalesReportExport;
use App\Models\Product\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function viewStock()
{
    $products = Product::select('name', DB::raw('MAX(id) as id'), DB::raw('MAX(price) as price'), DB::raw('SUM(quantity) as quantity'))
        ->groupBy('name')
        ->orderBy('id', 'asc')
        ->get();

    return view('admins.stock', compact('products'));
}

public function updateStock(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:0',
    ]);

    $product = Product::findOrFail($id);
    $product->quantity = $request->quantity;
    $product->save();

    return redirect()->route('admin.stock')->with('success', 'Stock updated successfully!');
}



public function salesReport()
{
    $sales = Order::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(price) as total_sales'),
        DB::raw('COUNT(id) as total_orders')
    )
    ->groupBy('date')
    ->orderBy('date', 'desc')
    ->limit(30)
    ->get();

    return view('admins.sales', compact('sales'));


}
public function lowStock()
{
    $lowStockProducts = Product::where('quantity', '<', 5)->get();
    return view('admins.low_stock', compact('lowStockProducts'));
}
}
