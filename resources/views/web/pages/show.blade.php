<!-- resources/views/web/pages/show.blade.php -->
@extends('layouts.main')

@section('content')
<!-- Контент страницы -->
<h1>{{ $page->title }}</h1>
<div>{!! $page->content !!}</div>
@endsection