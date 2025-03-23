@extends('layouts.admin')

@section('content')
  <div class="container">
    <h1>{{ $contact->name }}</h1>

    <!-- Email -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="contact_email" style="font-weight: normal; margin-right: 10px;">Email:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $contact->email }}
      </div>
    </div>

    <!-- Телефон -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="contact_phone" style="font-weight: normal; margin-right: 10px;">Телефон:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $contact->phone }}
      </div>
    </div>

    <!-- Адрес -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="contact_address" style="font-weight: normal; margin-right: 10px;">Адрес:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $contact->address }}
      </div>
    </div>

    <!-- Пользователь -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="contact_user" style="font-weight: normal; margin-right: 10px;">Пользователь:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $contact->user_id ? $contact->user->name : 'Не указан' }}
      </div>
    </div>

    <!-- Заказ -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="contact_order" style="font-weight: normal; margin-right: 10px;">Заказ:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $contact->order_id ? $contact->order->order_number : 'Не указан' }}
      </div>
    </div>

    <a href="{{ route('admin.contacts.index') }}" class="btn btn-primary">Назад к списку контактов</a>
  </div>
@endsection
