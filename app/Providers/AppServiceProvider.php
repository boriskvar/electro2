<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Category;
use App\View\Composers\CartComposer;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Route; // Добавляем Route
use Illuminate\Support\Facades\View;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\FooterController;
use App\Models\Wishlist;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    /* public function boot(): void
    {
                // Проверяем, существуют ли таблицы перед выполнением запросов
                if (Schema::hasTable('menus') && Schema::hasTable('categories') && Schema::hasTable('pages')) {
            
        // Загружаем только активные меню
        $menus = Menu::where('is_active', 1)
            ->orderBy('position')
            ->get();

        // Загружаем все категории и страницы
        $categories = Category::where('active', 1)->get()->keyBy('id'); // Используем keyBy('id') для быстрого поиска
        $pages = Page::all()->keyBy('id'); // То же самое для страниц

        // Преобразуем меню, добавляя связанные данные
        $menus->transform(function ($menu) use ($categories, $pages) {
            if ($menu->category_id && isset($categories[$menu->category_id])) {
                $menu->category = $categories[$menu->category_id]; // Присваиваем категорию
            }
            if ($menu->page_id && isset($pages[$menu->page_id])) {
                $menu->page = $pages[$menu->page_id]; // Присваиваем страницу
            }
            return $menu;
        });

        // Разделяем меню по типам
        $mainMenu = $menus->where('menu_type', 'main')->values();
        $footerMenu = $menus->where('menu_type', 'footer')->values();

        // Генерация хлебных крошек
        $breadcrumbs = [];
        $breadcrumbs[] = ['name' => 'Home', 'url' => url('/')]; // Заголовок "Home"

        $params = request()->segments();
        $url = url('/');

        // Добавляем только последний пункт в крошки
        if (!empty($params)) {
            $lastParam = end($params); // Последний сегмент
            $breadcrumbs[] = ['name' => ucfirst($lastParam), 'url' => '']; // Последний пункт без ссылки
        }

        // Делаем переменные доступными во всех шаблонах
        View::share(compact('mainMenu', 'footerMenu', 'breadcrumbs'));

        // Загружаем ссылки для футера через View Composer
        View::composer('partials.footer', function ($view) {
            $footerLinks = (new FooterController)->getFooterLinks();
            $view->with('footerLinks', $footerLinks);
        });

        // Зарегистрируйте View Composer для вашего компонента
        View::composer('components.main-header', CartComposer::class); //загружает данные через CartComposer, и делает переменные доступными в компоненте components.main-header
        // View::composer('*', CartComposer::class);

        View::share('categories', Category::all()); //Теперь переменная $categories будет доступна во всех шаблонах, включая хедер.

        View::composer('*', function ($view) {
            $wishlistCount = 0;

            if (Auth::check()) {
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            }

            $view->with('wishlistCount', $wishlistCount);
        });
    } */
}
