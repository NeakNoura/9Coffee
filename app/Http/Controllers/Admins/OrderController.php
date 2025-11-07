<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Order;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Redirect;



class OrderController extends Controller
{
    public function DisplayAllOrders(){
      $allOrders = Order::select()->orderBy('created_at','desc') ->paginate(10);;

        return view('admins.allorders',compact('allOrders'));
    }
    public function EditOrders($id){
        $order = Order::find($id);

          return view('admins.editorders',compact('order'));
      }

    public function UpdateOrders(Request $request, $id){
    $order = Order::find($id);
    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Order not found']);
    }

    $request->validate([
        'status' => 'required|in:Pending,Delivered,Cancelled'
    ]);

    $order->status = $request->status;
    $order->save();

    return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
}



     public function DeleteOrders($id){
    $order = Order::find($id);
    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Order not found']);
    }

    $order->delete();
    return response()->json(['success' => true, 'message' => 'Order deleted successfully']);
}

        public function DeleteAllOrders()
        {
           Order::query()->delete();

            return Redirect::route('all.orders')->with(['delete' => "All orders deleted successfully"]);
        }

      public function DisplayProducts(){
        $products = Product::select()->orderBy('id','asc')->get();


            return view('admins.allproducts',compact('products'));

      }
public function orderProduct(Request $request)
{
    // OrderController::orderProduct
$product = Product::findOrFail($request->product_id);
$quantity = $request->quantity;

// check stock
foreach ($product->rawMaterials as $raw) {
    if ($raw->quantity < $raw->pivot->quantity_required * $quantity) {
        return back()->with('error', $raw->name . ' is not enough!');
    }
}

// Deduct ingredients
$product->deductIngredients($quantity);

    // Create order
    Order::create([
        'product_id' => $product->id,
        'quantity' => $quantity,
        'price' => $product->price * $quantity,
        'status' => 'Paid Successfully',
    ]);

    return back()->with('success', 'Order placed and stock updated!');
}


}
