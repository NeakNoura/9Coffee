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


}
