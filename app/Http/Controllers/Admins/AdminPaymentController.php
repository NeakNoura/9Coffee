<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product\Order; // Make sure you have an Order model
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
}
