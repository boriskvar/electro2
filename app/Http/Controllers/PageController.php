<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        // Получаем страницу по slug из базы данных
        $page = Page::where('slug', $slug)->firstOrFail();

        // Проверяем наличие представления, связанного с этим slug (В зависимости от slug, динамически формируем путь к представлению (web.pages.about-us, web.pages.contact и т.д.).)
        $view = 'web.pages.' . $slug;

        // Проверяем, существует ли файл представления с таким путем
        if (view()->exists($view)) {
            return view($view, compact('page'));
        }
        // Если представление не существует, можно отобразить 404 или другое представление
        return abort(404);
    }
}