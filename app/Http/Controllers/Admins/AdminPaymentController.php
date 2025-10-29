<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Order; // Order model
use App\Models\Product\Product; // Product model
use Illuminate\Support\Facades\Session;



class AdminPaymentController extends Controller
{
    public function cashPayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $amount = $request->input('amount');

        // Find the order
        $order = Order::find($orderId);
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        // Mark as paid
        $order->payment_method = 'Cash';
        $order->status = 'Paid';
        $order->total_paid = $amount;
        $order->save();

        // Clear cart session
        Session::forget('admin_cart');
        Session::forget('admin_cart_total');

        return redirect()->route('admins.dashboard')
                         ->with('success', 'Payment successful! Order marked as paid in cash.');
    }


public function qrPay($order_ref)
{
    $orders = Order::where('order_ref', $order_ref)->get();

    if($orders->isEmpty()) {
        return "Order not found!";
    }

    // Mark payment as successful
    foreach($orders as $order){
        $order->update(['payment_status' => 'Paid']);
    }

    return "Payment successful! Thank you.";
}
public function paywithPaypal()
{
    $cart = session('admin_cart', []);
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return view('admins.paypal-checkout', compact('total'));
}

public function paypalSuccess()
{
    $cart = session('admin_cart', []);
    $total = session('admin_cart_total', 0);

    if (empty($cart)) {
        return redirect()->route('staff.sell.form')->with('error', 'No cart data found!');
    }

    foreach ($cart as $productId => $item) {
        $product = Product::find($productId);
        if (!$product) continue;

        $order = Order::create([
    'product_id' => $product->id,
    'price' => $item['price'] * $item['quantity'],
    'payment_status' => 'Paid',
    'status' => 'Completed',
    'first_name' => 'Staff',
    'last_name' => '',
    'state' => 'POS Sale',
    'user_id' => auth('admin')->id() ?? null,
    'address' => 'N/A',  // mandatory
    'city' => 'N/A',
    'zip_code' => '00000',
    'phone' => '0000000000',
    'email' => 'staff@pos.local'
]);


        // Deduct stock
        if ($product->quantity >= $item['quantity']) {
            $product->quantity -= $item['quantity'];
            $product->save();
        }
    }

    session()->forget('admin_cart');
    session()->forget('admin_cart_total');

    return view('admins.paypal-success')->with('success', 'Payment and order recorded successfully!');
}
}
