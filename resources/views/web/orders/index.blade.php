@extends('layouts.layout')

@section('content')
  <div class="container">
    <h1>Ваши заказы</h1>

    @if ($orders->isEmpty())
      <p>У вас нет заказов.</p>
    @else
      <table class="table">
        <thead>
          <tr>
            <th>ID заказа</th>
            <th>Дата</th>
            <th>Сумма</th>
            <th>Статус</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($orders as $order)
            <tr>
              <td>{{ $order->id }}</td>
              <td>{{ $order->created_at }}</td>
              <td>{{ $order->total }}</td>
              <td>{{ $order->status }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
@endsection
