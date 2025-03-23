@extends('layouts.admin')

@section('content')
  <div class="container">
    {{-- {{ dd($brand->toArray()) }} --}}
    <h1>Бренд: {{ $brand->name }}</h1>

    <div class="brand-details">
      <p><strong>Slug:</strong> {{ $brand->slug }}</p>
      <p><strong>Описание:</strong> {{ $brand->description }}</p>
      <p><strong>Популярность:</strong> {{ $brand->popularity }}</p>
      <p><strong>Логотип:</strong></p>

      @php
        // Путь к логотипу
        $logoPath = 'storage/logos/' . basename($brand->logo);
      @endphp

      @if ($brand->logo && file_exists(public_path($logoPath)))
        <img src="{{ asset($logoPath) }}"
             alt="{{ $brand->name }} Logo"
             class="brand-logo"
             style="max-width: 200px;">
      @else
        <p>Логотип отсутствует.</p>
      @endif
    </div>

    <a href="{{ route('admin.brands.index') }}" class="btn btn-warning">Назад к списку брендов</a>
  </div>
@endsection
