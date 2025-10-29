<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Order;
use App\Models\RawMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    public function StaffSellForm()
    {
        $products = Product::select()->orderBy('id','asc')->get();
        return view('admins.staffSell', compact('products'));
    }
public function StaffSellProduct(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'payment_status' => 'required|in:Paid,Due',
        'first_name' => 'sometimes|string|max:255',
        'last_name' => 'sometimes|string|max:255',
        'state' => 'sometimes|string|max:255',
    ]);

    $product = Product::find($request->product_id);

    // Check if enough stock
    if ($product->quantity < $request->quantity) {
        return redirect()->back()->with('error', 'Not enough stock!');
    }

    $totalPrice = $product->price * $request->quantity;


    $order = Order::create([
        'product_id' => $product->id,
        'price' => $totalPrice,
        'payment_status' => $request->payment_status ?? 'Due',
        'status' => 'Pending',
        'first_name' => $request->first_name ?? 'Staff',
        'last_name' => $request->last_name ?? '',
        'state' => $request->state ?? '',
        'user_id' => Auth::id(),
    ]);

    // Deduct sold quantity from product stock
    $product->quantity -= $request->quantity;
    $product->save();

    return redirect()->route('staff.sell.form')->with(['success' => 'Product sold successfully!']);
}

public function staffCheckout(Request $request)
{
    $cart = json_decode($request->cart_data, true);
    $paymentMethod = $request->payment_method;
    $orderRef = 'ORD-' . now()->format('YmdHis') . '-' . rand(1000,9999);

    $ordersCreated = [];

    foreach ($cart as $key => $item) {
        $parts = explode('_', $key);
        $productId = $parts[0];
        $size = $parts[1] ?? 'S';
        $sugar = $parts[2] ?? '50';

        $product = Product::with('rawMaterials')->find($productId);
        if (!$product) continue;

        $canFulfill = true;
        foreach ($product->rawMaterials as $raw) {
            $requiredQty = $raw->pivot->quantity_required * $item['quantity'];
            if ($raw->quantity < $requiredQty) {
                $canFulfill = false;
                break;
            }
        }
        if (!$canFulfill) continue;

        $order = Order::create([
            'product_id' => $productId,
            'price' => $item['price'] * $item['quantity'],
            'quantity' => $item['quantity'],
            'size' => $size,
            'sugar' => $sugar,
            'payment_status' => 'Paid',
            'status' => 'Paid',
            'first_name' => 'Staff',
            'last_name' => '',
            'state' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'user_id' => Auth::id(),
            'payment_method' => $paymentMethod,
            'order_ref' => $orderRef,
        ]);

        $product->decrement('quantity', $item['quantity']);

        foreach ($product->rawMaterials as $raw) {
            $requiredQty = $raw->pivot->quantity_required * $item['quantity'];
            $raw->decrement('quantity', $requiredQty);
        }

        $ordersCreated[] = $order;
    }
    foreach ($product->rawMaterials as $raw) {
    $requiredQty = $raw->pivot->quantity_required * $item['quantity'];
    \Log::info("Deducting {$requiredQty} from {$raw->name} (before: {$raw->quantity})");
    $raw->decrement('quantity', $requiredQty);
}


    // Return updated raw material stock
    $updatedRawMaterials = RawMaterial::all()->map(function($raw){
        return [
            'id' => $raw->id,
            'name' => $raw->name,
            'quantity' => $raw->quantity,
            'unit' => $raw->unit,
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'Checkout completed!',
        'orders' => $ordersCreated,
        'updated_raw_materials' => $updatedRawMaterials,
    ]);
}
}
