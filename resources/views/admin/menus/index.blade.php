@extends('layouts.admin')

@section('content')
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="mb-0">Список Меню</h1>
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">Добавить Меню</a>
    </div>

    @if ($menus->isEmpty())
      <p>Нет доступных меню.</p>
    @else
      <table class="table">
        <thead>
          <tr>
            <th>Название</th>
            <th>Активен</th>
            <th>Позиция</th>
            <th>Родитель</th>
            <th>Slug</th>
            <th>Меню</th>
            <th>Нижнее меню</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($menus as $menu)
            <tr>
              <td>
                <a href="{{ route('admin.menus.show', $menu->id) }}">{{ $menu->name }}</a>
              </td>

              <td>
                <span class="{{ $menu->is_active ? 'text-success' : 'text-danger' }}">
                  {{ $menu->is_active ? 'Да' : 'Нет' }}
                </span>
              </td>
              <td>{{ $menu->position }}</td>
              <td>{{ $menu->parent_id ? \App\Models\Menu::find($menu->parent_id)->name : 'Нет' }}</td>
              <td>{{ $menu->slug }}</td>
              <td>{{ $menu->menu_type }}</td>
              <td>{{ $menu->type }}</td>

              <td>
                <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                <a href="{{ route('admin.menus.copy', $menu->id) }}" class="btn btn-sm btn-warning">Копировать</a>
                <form action="{{ route('admin.menus.destroy', $menu->id) }}"
                      method="POST"
                      style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  <script>
    document.querySelectorAll('.position-input').forEach(input => {
      input.addEventListener('change', function() {
        let id = this.dataset.id;
        let position = this.value;

        fetch(`/admin/menus/${id}/update-position`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              position: position
            })
          }).then(response => response.json())
          .then(data => alert(data.message));
      });
    });
  </script>
@endsection
