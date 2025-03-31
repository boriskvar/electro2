@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Сравнения товаров</h2>

    <!-- Кнопка для добавления товара в сравнение (без привязки к конкретному сравнению) -->
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addProductModal">
        Добавить товар в сравнение
    </button>

    <!-- Модальное окно для добавления товара в сравнение -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Добавить товар в сравнение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="product_id">Выберите товар</label>
                            <select name="product_id" id="product_id" class="form-control">
                                @foreach ($allProducts as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Кол-во товаров</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comparisons as $comparison)
            <tr>
                <td>{{ $comparison->id }}</td>
                <td>{{ $comparison->user_id }}</td>
                <td>{{ $comparison->products->count() }}</td>
                <td>
                    <!-- Добавление кнопки для открытия модального окна -->
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addProductModal" data-comparison-id="{{ $comparison->id }}">
                        Добавить товар в сравнение
                    </button>

                    <a href="{{ route('admin.comparisons.show', $comparison) }}" class="btn btn-info">Просмотр</a>
                    <form action="{{ route('admin.comparisons.destroy', $comparison) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $comparisons->links() }}
</div>

<!-- Подключение скриптов для работы с модальным окном -->
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Скрипт для передачи ID сравнения в форму
    $('#addProductModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget) // Кнопка, которая откроет модальное окно
        var comparisonId = button.data('comparison-id') // Извлекаем ID сравнения из кнопки
        var modal = $(this)
        var form = modal.find('form');

        // Обновляем атрибут action формы с правильным ID сравнения
        form.attr('action', '/admin/comparisons/' + comparisonId + '/products');
    });
</script>
@endpush

@endsection