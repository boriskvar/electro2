<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Contact;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::first();
        $cartCount = Cart::count(); // Количество товаров в корзине
        $wishlistCount = Wishlist::count(); // Количество товаров в списке желаемого
        // $user = auth()->user(); // Текущий пользователь (если он авторизован)
        $userId = 1; // Временный ID пользователя
        $breadcrumbs = [
            ['name' => 'Главная', 'url' => route('home')],
            ['name' => 'Контакты', 'url' => route('contacts.index')],
        ];
        return view('web.contacts.index', compact('contacts','cartCount', 'wishlistCount', 'userId', 'breadcrumbs'));
    }

    public function create()
    {
        // Логика для отображения формы создания контакта, если требуется
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:contacts,email',
        ]);

        $contact = Contact::create($validatedData);
        return response()->json($contact, 201);
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json($contact);
    }

    public function edit($id)
    {
        // Логика для отображения формы редактирования контакта, если требуется
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:contacts,email,' . $contact->id,
        ]);

        $contact->update($validatedData);
        return response()->json($contact);
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return response()->json(null, 204);
    }
}