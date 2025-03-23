<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminContactController extends Controller
{
    // Метод для получения всех контактов
    public function index()
    {
        // Получаем контакты с пагинацией (по 10 записей на страницу)
        // $contacts = Contact::paginate(10);
        
        // Получаем все контакты из базы данных
        $contacts = Contact::all();

        
        return  view('admin.contacts.index', ['contacts' => $contacts]);
    }

    // Метод для отображения формы создания нового контакта
    public function create()
    {
        // Получаем всех пользователей для выпадающего списка
        $users = User::all();
        
        return view('admin.contacts.create', compact('users'));
    }

    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'user_id' => 'required|exists:users,id', // проверка, что user_id существует в таблице users
            'order_id' => 'nullable|exists:orders,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:contacts,email',
        ]);

        // Создание нового контакта
        $contact = Contact::create($request->only(['user_id', 'order_id', 'name', 'address', 'phone', 'email']));
        
        // Перенаправление или возврат ответа
        return redirect()->route('admin.contacts.index')->with('success', "Контакт '{$contact->name}' успешно создан!");
    }

    public function show(Contact $contact)
    {
        // Получаем контакт по его ID
        return view('admin.contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        // Получаем всех пользователей для выпадающего списка
        $users = User::all();
        
        // Логика для отображения формы редактирования контакта, если требуется
        return view('admin.contacts.edit', compact('contact', 'users'));
    }

    public function update(Request $request, Contact $contact)
    {
        // Валидация данных
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:contacts,email,' . $contact->id, // Уникальность email, исключая текущий контакт
        ]);
    
        // Обновление данных контакта
        $contact->update($request->only(['user_id', 'order_id', 'name', 'address', 'phone', 'email']));
    
        // Перенаправление или возврат ответа
        return redirect()->route('admin.contacts.index')->with('success', "Контакт '{$contact->name}' успешно обновлен!");
    }
    

    public function destroy(Contact $contact)
    {
        // Удаляем контакт
        $contact->delete();
        
        // Переадресация на список контактов с сообщением об успешном удалении
        return redirect()->route('admin.contacts.index')->with('success', 'Контакт успешно удален!');
    }
    
    
}