@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Добавить социальную ссылку</h2>

    @include('admin.partials.messages')

    <form action="{{ route('admin.social-links.store') }}" method="POST">
        @csrf

        @include('admin.social_links.form')

        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{ route('admin.social-links.index') }}" class="btn btn-default">Назад</a>
    </form>
</div>
@endsection