<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SearchQuery; // Предположим, что у нас есть таблица с поисковыми запросами
use Illuminate\Support\Facades\DB;

class SearchAnalyticsController extends Controller
{
    // Метод для отображения статистики поисковых запросов
    public function index()
    {
        $searchQueries = SearchQuery::paginate(10); // Пагинация по 10 запросов
        $noResultsQueries = SearchQuery::where('results_count', 0)
            ->select('query', DB::raw('count(*) as count'))
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('admin.search_analytics.index', compact('searchQueries', 'noResultsQueries'));
    }
}
