@extends('layouts.app')

@section('content')
<h1>Мой аккаунт</h1>
<ul>
    <li><a href="{{ route('wishlist.index') }}">Мои желания</a></li>
    <li><a href="#">Мои заказы</a></li>
    <li><a href="{{ route('profile.edit') }}">Настройки профиля</a></li>
</ul>
@endsection