<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\Factory as ViewFactory; // 👈 Важно: НЕ фасад!

use App\Models\SocialLink;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot(ViewFactory $view) // 👈 внедрение зависимости view
    {
        $view->composer(
            ['product', 'components.newsletter'], // 👈 Подставь свои шаблоны
            function ($view) {
                $view->with('socialLinks', SocialLink::where('active', true)->orderBy('position')->get());
            }
        );
    }

    public function register()
    {
        //
    }
}
