@extends('layouts.admin')

@section('content')
  {{-- {{ dd($products->toArray()) }} --}}
  <div class="container">
    <h1>Список Контактов</h1>

    <!-- Отображение сообщений об ошибках -->
    @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

    <!-- Отображение сообщений об успехе -->
    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    <!-- Кнопка Добавить -->
    <a href="{{ route('admin.contacts.create') }}" class="btn btn-primary">Добавить контакт</a>
    <table class="table">
      <thead>
        <tr>
          <th>Имя</th>
          <th>Email</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        @if (isset($contacts) && $contacts->isNotEmpty())
          @foreach ($contacts as $contact)
            <tr>
              <td><a href="{{ route('admin.contacts.show', $contact->id) }}">{{ $contact->name }}</a>
              <td>{{ $contact->email }}</td>
              <td>
                <!-- Показать -->
                {{-- <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-info">Просмотр</a> --}}
                <!-- Редактировать -->
                <a href="{{ route('admin.contacts.edit', $contact->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                <!-- Удалить -->
                <form action="{{ route('admin.contacts.destroy', $contact->id) }}"
                      method="POST"
                      onsubmit="return confirm('Вы уверены, что хотите удалить этот контакт?');"
                      style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                </form>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="3">Нет доступных контактов.</td>
          </tr>
        @endif
      </tbody>
    </table>



    <!-- Пагинация -->
    {{-- @if (isset($contacts))
    <div class="mt-3">
        {{ $contacts->links() }}
</div>
@endif --}}
  </div>
@endsection
