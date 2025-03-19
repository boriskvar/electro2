<!-- resources/views/web/categories/show.blade.php -->
@extends('web.store')  <!-- Расширяем шаблон магазина -->

@section('category-content')  <!-- Контент для категории -->
     <!-- Контент категории -->
     <h1>{{ $category->name }}</h1>
     <div>{!! $category->description !!}</div>
@endsection
