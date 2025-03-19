@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Добавить новую страницу</h1>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <form action="{{ route('admin.pages.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Название страницы:</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"  placeholder="Введите название" value="{{ old('title') }}" required>
            @error('title')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" placeholder="Введите слаг" value="{{ old('slug') }}"  required>
            @error('slug')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">Описание:</label>
            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4">{{ old('content') }}</textarea>
            @error('content')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- <div class="form-group">
            <label for="category_id">Категория страницы:</label>
            <select class="form-control" id="category_id" name="category_id">
                <option value="">Без категории</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
        </select>
</div> --}}

<button type="submit" class="btn btn-primary">Создать страницу</button>
<a href="{{ route('admin.pages.index') }}" class="btn btn-warning">Назад</a>
</form>
</div>
@endsection