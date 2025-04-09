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

        // return view('user.orders.success', compact('order'));
        return redirect()->route('home')->with('success', 'Заказ оформлен успешно.');


        /* return view('user.orders.success', [
    'order' => $order,
    'message' => 'Ваш заказ успешно оформлен!'
]); 
*/

        /* return redirect()
            ->route('home')
            ->with('success', 'Заказ №' . $order->id . ' успешно оформлен!');*/
    }
}
