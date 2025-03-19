<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        // Валидация
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email'
        ]);

        // Сохранение подписки
        $subscription = NewsletterSubscription::create([
            'email' => $request->email
        ]);

        // Возвращаем успешный ответ
        return redirect()->back()->with('message', 'You have successfully subscribed to our newsletter!')
                             ->with('message_class', 'alert-success');
    }
}