<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        // Здесь можно вернуть форму для создания платежа
        $orders = Order::all(); // Make sure Order model includes 'order_number'
        return view('admin.payments.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $payment = Payment::create($validated);
        return response()->json($payment, 201);
    }

    public function show(Payment $payment)
    {
        return response()->json($payment);
    }

    public function edit(Payment $payment)
    {
        // Здесь можно вернуть форму для редактирования платежа
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_method' => 'sometimes|required|string',
            'amount' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|string',
        ]);

        $payment->update($validated);
        return response()->json($payment);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(null, 204);
    }
}
