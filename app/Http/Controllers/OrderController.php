<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function success($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Заказ не найден');
        }

        return view('user.orders.success', compact('order'));
    }
}
