@extends('layouts.main')

@section('content')
@include('web.quick-view.modal', ['product' => $product, 'attributes' => $attributes])
@endsection