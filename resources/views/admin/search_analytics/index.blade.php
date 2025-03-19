@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Популярные запросы</h2>
    @if(isset($searchQueries))
    <p>Поисковые запросы загружены.</p>
    @else
    <p>Поисковые запросы не найдены.</p>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Query</th>
                <th>Results Count</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($searchQueries as $query)
            <tr>
                <td>{{ $query->id }}</td>
                <td>{{ $query->user_id }}</td>
                <td>{{ $query->query }}</td>
                <td>{{ $query->results_count }}</td>
                <td>{{ $query->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No search queries found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Запросы без результатов</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Запрос</th>
                <th>Количество</th>
            </tr>
        </thead>
        <tbody>
            @if($noResultsQueries->isEmpty())
            <tr>
                <td colspan="2">No queries found without results.</td>
            </tr>
            @else
            @foreach ($noResultsQueries as $query)
            <tr>
                <td>{{ $query->query }}</td>
                <td>{{ $query->count }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

    {{-- Пагинация --}}
    <div class="d-flex justify-content-center">
        {{ $searchQueries->links() }}
    </div>
</div>
@endsection
