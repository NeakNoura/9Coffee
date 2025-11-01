<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Order;
use Illuminate\Support\Facades\DB;
use App\Models\RawMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
// Example in StaffController
public function StaffSellForm()
{
    $products = Product::with('rawMaterials')->orderBy('id','asc')->get();
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

    if(empty($cart) || !is_array($cart)){
        return response()->json([
            'success' => false,
            'message' => 'Cart is empty or invalid!'
        ]);
    }

    $updatedStock = [];
    $totalAmount = 0;

    \DB::beginTransaction();
    try {
        foreach($cart as $key => $item){
            $parts = explode('_', $key);
            $productId = $parts[0];
            $size = $parts[1] ?? 'S';
            $sugar = $parts[2] ?? '50';

            $product = Product::with('rawMaterials')->find($productId);
            if(!$product) continue;

            // Check available stock
            if($item['quantity'] > $product->available_stock){
                \DB::rollBack();
                return response()->json([
                    'success'=>false,
                    'message'=>"Not enough stock for {$product->name}"
                ]);
            }

            // Deduct ingredients
            $product->deductIngredients($item['quantity']);

           $product->orders()->create([
    'user_id'        => auth()->id(),        // Staff ID
    'product_id'     => $product->id,
    'quantity'       => $item['quantity'],
    'size'           => $size,
    'sugar'          => $sugar,
    'first_name'     => 'Walk-in',           // required
    'last_name'      => 'Customer',          // optional
    'price'          => $item['price'],
    'status'         => 'Paid Successfully',
    'payment_status' => 'Cash',
    'payment_method' => $paymentMethod,
]);


            $totalAmount += $item['price'] * $item['quantity'];

            // Save updated stock for front-end
            $updatedStock[$productId] = $product->available_stock - $item['quantity'];
        }

        // Update cashier balance (example: simple addition)
        $cashier = auth()->user(); // assuming staff user
        $cashier->balance = ($cashier->balance ?? 0) + $totalAmount;
        $cashier->save();

        \DB::commit();

        return response()->json([
            'success'=>true,
            'message'=>'Checkout successful!',
            'updated_stock'=>$updatedStock
        ]);

    } catch (\Exception $e){
        \DB::rollBack();
        return response()->json([
            'success'=>false,
            'message'=>$e->getMessage()
        ]);
    }
}


}
