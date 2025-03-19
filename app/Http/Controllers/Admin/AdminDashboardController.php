<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchQuery;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Получаем популярные поисковые запросы
        $searchQueries = SearchQuery::paginate(10); // Пагинация по 10 запросов

        // Получаем запросы без результатов
        $noResultsQueries = SearchQuery::where('results_count', 0)
            ->select('query', DB::raw('count(*) as count'))
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Возвращаем представление с данными
        return view('admin.dashboard', compact('searchQueries', 'noResultsQueries'));
    }
}