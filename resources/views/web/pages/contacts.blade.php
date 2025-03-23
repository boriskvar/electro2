<!-- resources/views/pages/contact.blade.php -->

@extends('layouts.layout')

@section('content')
  <h1>Контакты</h1>

  <!-- Проверка наличия контактных данных -->
  {{-- @if ($contacts)
    <ul>
      <li><a href="tel:{{ $contacts->phone }}"><i class="fa fa-phone"></i> {{ $contacts->phone }}</a></li>
      <li><a href="mailto:{{ $contacts->email }}"><i class="fa fa-envelope-o"></i> {{ $contacts->email }}</a></li>
      <li><a href="https://www.google.com/maps?q={{ urlencode($contacts->address) }}" target="_blank"><i
             class="fa fa-map-marker"></i> {{ $contacts->address }}</a></li>
    </ul>
  @else
    <p>Контактная информация не доступна.</p>
  @endif --}}
@endsection
