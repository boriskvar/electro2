@props(['breadcrumbs'])

@if (!empty($breadcrumbs) && Route::currentRouteName() !== 'home')
  <div id="breadcrumb">
    <div class="container">
      <h3 class="breadcrumb-header">{{ end($breadcrumbs)['name'] }}</h3> {{-- Заголовок с названием последнего элемента --}}

      <ul class="breadcrumb-tree">
        {{-- Первый элемент: всегда "Home" --}}
        <li><a href="{{ url('/') }}">Home</a></li>

        {{-- Добавляем только последний пункт в хлебных крошках --}}
        @foreach ($breadcrumbs as $key => $breadcrumb)
          @if ($loop->last)
            {{-- Только для последнего элемента --}}
            <li><span class="active">{{ $breadcrumb['name'] }}</span></li>
          @endif
        @endforeach
      </ul>
    </div>
  </div>
@endif
