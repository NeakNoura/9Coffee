<?php
namespace App\Http\Controllers\Admins;
use App\Models\Product\Product;
use App\Models\ProductType;
use App\Models\SubType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class StaffController extends Controller
{


public function StaffSellForm()
{
    $products = Product::with('type', 'subType', 'rawMaterials')->orderBy('id','asc')->get();
    $productsType = $products->groupBy(fn($p) => strtolower($p->type->name ?? 'others'));
    $types = ProductType::all();
    $subTypes = SubType::all();
    $earning = Order::sum('price');
    return view('admins.staffSell', compact('products', 'productsType', 'types', 'subTypes', 'earning'));
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
    $product->quantity -= $request->quantity;
    $product->save();

    return redirect()->route('staff.sell.form')->with(['success' => 'Product sold successfully!']);
}

public function staffCheckout(Request $request)
{
    $cart = json_decode($request->cart_data, true);
    $paymentMethod = $request->payment_method;

    if(empty($cart) || !is_array($cart)){
        return response()->json(['success'=>false,'message'=>'Cart is empty or invalid!']);
    }

    $updatedStock = [];
    $totalAmount = 0;

    DB::beginTransaction();
    try {
        foreach($cart as $key => $item){
            $productId = $item['id'];
            $size = $item['size'] ?? 'S';
            $sugar = $item['sugar'] ?? '50';

            $product = Product::find($productId);
            if(!$product) continue;

            if($item['quantity'] > $product->quantity){
                DB::rollBack();
                return response()->json(['success'=>false,'message'=>"Not enough stock for {$product->name}"]);
            }

            // Deduct stock
            $product->quantity -= $item['quantity'];
            $product->save();

            // Total line price
            $lineTotal = $item['unit_price'] * $item['quantity'];

            // Store order
            $product->orders()->create([
                'user_id'        => auth()->id(),
                'product_id'     => $product->id,
                'quantity'       => $item['quantity'],
                'size'           => $size,
                'sugar'          => $sugar,
                'first_name'     => 'Walk-in',
                'last_name'      => 'Customer',
                'price'          => $lineTotal,   // total for this line
                'status'         => 'Paid Successfully',
                'payment_status' => 'Cash',
                'payment_method' => $paymentMethod,
            ]);

            $totalAmount += $lineTotal;
            $updatedStock[$productId] = $product->quantity;
        }

        // Update staff balance
        $cashier = auth()->user();
        $cashier->balance = ($cashier->balance ?? 0) + $totalAmount;
        $cashier->save();

        DB::commit();

        return response()->json(['success'=>true,'message'=>'Checkout successful!','updated_stock'=>$updatedStock]);

    } catch (\Exception $e){
        DB::rollBack();
        return response()->json(['success'=>false,'message'=>$e->getMessage()]);
    }
}





}
