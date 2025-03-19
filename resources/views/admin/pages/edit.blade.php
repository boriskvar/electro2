@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Редактировать страницу: {{ $page->title }}</h1>

    <!-- Сообщения об ошибках -->
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Сообщение об успехе -->
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Название страницы:</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="title" name="title" value="{{ old('title', $page->title) }}" required>
            @error('title')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug"  value="{{ old('slug', $page->slug) }}" required>
            @error('slug')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">Описание:</label>
            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4">{{ old('content', $page->content) }}</textarea>
            @error('content')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- <div class="form-group">
            <label for="category_id">Категория страницы:</label>
            <select class="form-control" id="category_id" name="category_id">
                <option value="">Без категории</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $page->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
        </select>
</div> --}}

<button type="submit" class="btn btn-primary">Сохранить изменения</button>
<a href="{{ route('admin.pages.index') }}" class="btn btn-warning">Назад</a>
</form>
</div>
@endsection