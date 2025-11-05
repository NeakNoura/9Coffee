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
    $products = Product::all();
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

    return redirect()->back()->with('success', 'Stock updated successfully!');
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
    $allProducts = Product::with('rawMaterials')->get();

    // Filter by calculated available_stock
    $lowStockProducts = $allProducts->filter(function($product){
        return $product->available_stock <= 5; // threshold
    });

    return view('admins.low_stock', compact('lowStockProducts'));
}
public function addQuantity(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($id);
    $product->quantity += $request->quantity;
    $product->save();

    return response()->json([
        'success' => true,
        'new_quantity' => $product->quantity,
        'message' => $product->name . ' stock updated!',
    ]);
}


}
