@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>{{ $page->title }}</h1>

        <!-- Имя страницы -->
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="product_name" style="font-weight: normal; margin-right: 10px;">Название страницы:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $page->title }}
            </div>
        </div>

    <div style="margin-bottom: 20px;">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="slug" style="font-weight: normal; margin-right: 10px;">Slug:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $page->slug }}
            </div>
        </div>



        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="description" style="font-weight: normal; margin-right: 10px;">Описание:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $page->content }}
            </div>
        </div>

    <div class="mt-4">
        
        <a href="{{ route('admin.pages.index') }}" class="btn btn-warning">Назад</a>
    </div>
</div>
@endsection