<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function getFooterLinks()
    {
        return [
            'categories' => Menu::where('menu_type', 'footer')
                ->whereNotNull('category_id')  // Проверяем наличие категории
                ->where('is_active', 1)
                ->orderBy('position')
                ->get()
                ->map(function ($menuItem) {
                    if ($menuItem->category_id) {
                        $category = Category::find($menuItem->category_id);  // Получаем категорию
                        $menuItem->url = route('menus.category.show', [
                            'slug' => $menuItem->slug,  // slug меню
                            'category_slug' => $category->slug,  // slug категории
                        ]);
                    }
                    return $menuItem;
                }),

            'information' => Menu::where('menu_type', 'footer')
                ->where('type', 'information')
                ->where('is_active', 1)
                ->orderBy('position')
                ->get()
                ->map(function ($menuItem) {

                    // dd($menuItem->slug);
                    // Получаем страницу по slug
                    $page = Page::where('slug', $menuItem->slug)->first();

                    if ($page) {
                        // Убедитесь, что генерируем правильную ссылку для страницы
                        // Явно указываем слаг для страницы
                        $menuItem->url = route('pages.show', ['slug' => $menuItem->slug]);
                    } else {
                        // Для случая, когда страница не найдена, можно оставить пустую ссылку
                        $menuItem->url = '#';  // или какой-то fallback
                    }

                    // dd($menuItem->url);

                    return $menuItem;
                }),






            'service' => Menu::where('menu_type', 'footer')
                ->where('type', 'service')
                ->where('is_active', 1)
                ->orderBy('position')
                ->get(),
        ];
    }
}