@extends('layouts.layout')

@section('content')
<h1>Search Results for "{{ $query }}"</h1>

@if($results->isEmpty())
<p>No results found.</p>
@else
<ul>
    @foreach($results as $product)
    <li>{{ $product->name }} - {{ $product->description }}</li>
    @endforeach
</ul>
@endif
@endsection
