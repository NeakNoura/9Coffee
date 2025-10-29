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
      $allOrders = Order::select()->orderBy('created_at','desc')->get();

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
            \App\Models\Product\Order::query()->delete();

            return Redirect::route('all.orders')->with(['delete' => "All orders deleted successfully"]);
        }

      public function DisplayProducts(){
        $products = Product::select()->orderBy('id','asc')->get();


            return view('admins.allproducts',compact('products'));

      }
public function orderProduct(Request $request)
{
    $product = Product::findOrFail($request->product_id);
    $quantity = $request->quantity;

    // Check if stock is enough
    foreach ($product->rawMaterials as $material) {
        if ($material->quantity < ($material->pivot->quantity_required * $quantity)) {
            return back()->with('error', $material->name . ' is not enough!');
        }
    }

    // Deduct raw materials
    foreach ($product->rawMaterials as $material) {
        $material->quantity -= $material->pivot->quantity_required * $quantity;
        $material->save();
    }

    // Create order
    Order::create([
        'product_id' => $product->id,
        'quantity' => $quantity,
        'price' => $product->price * $quantity,
        'status' => 'Pending'
    ]);

    return back()->with('success', 'Order placed and stock updated!');
}


}
