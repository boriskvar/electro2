@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Редактировать ссылку: {{ $socialLink->name }}</h2>

    @include('admin.partials.messages')

    <form action="{{ route('admin.social-links.update', $socialLink->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.social_links.form', ['socialLink' => $socialLink])

        <button type="submit" class="btn btn-primary">Обновить</button>
        <a href="{{ route('admin.social-links.index') }}" class="btn btn-default">Назад</a>
    </form>
</div>
@endsection