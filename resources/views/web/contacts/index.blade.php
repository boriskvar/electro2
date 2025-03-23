@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Контакты</h1>
    <ul>
        @forelse($contacts as $contact)
        <li>
            <strong>{{ $contact->name }}</strong><br>
            Адрес: {{ $contact->address ?? 'Не указан' }}<br>
            Телефон: {{ $contact->phone ?? 'Не указан' }}<br>
            Email: {{ $contact->email }}
        </li>
        @empty
        <p>Контактов нет.</p>
        @endforelse
    </ul>
</div>
@endsection