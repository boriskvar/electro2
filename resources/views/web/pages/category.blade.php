<!-- resources/views/web/pages/show.blade.php -->
@extends('web.store')  <!-- Расширяем шаблон магазина -->

@section('category-content')
<h2>Categories</h2>
<ul>
    @foreach($categories as $category)
        <li>{{ $category->name }}</li>
    @endforeach
</ul>
@endsection