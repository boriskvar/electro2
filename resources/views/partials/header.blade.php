<!-- HEADER -->

<header>

    <!-- TOP HEADER -->
    <x-top-header{{--  :contacts="$contacts" --}} />
    <!-- /TOP HEADER -->

    <!-- MAIN HEADER -->
    {{-- {{ dd($wishlistCount) }} --}}
    <x-main-header :wishlistCount="$wishlistCount" />
    <!-- /MAIN HEADER -->

</header>
<!-- /HEADER -->


<!-- NAVIGATION -->
<nav id="navigation">
    <div class="container">
        <div id="responsive-nav">
            <ul class="main-nav nav navbar-nav">
                @php
                $homeMenu = $mainMenu->firstWhere('is_home', true);
                $addedUrls = [];
                @endphp

                @if ($homeMenu)
                @php
                $homeUrl = $homeMenu->custom_url ? url($homeMenu->custom_url) : url('/');
                $addedUrls[] = $homeUrl;
                @endphp
                <li class="{{ request()->url() === $homeUrl ? 'active' : '' }}">
                    <a href="{{ $homeUrl }}">{{ $homeMenu->name }}</a>
                </li>
                @endif

                @foreach ($mainMenu as $item)
                @php
                // Пропускаем Home, если он уже добавлен
                if ($item->is_home) {
                continue;
                }
                // Формируем URL в зависимости от типа пункта меню
                $url = $item->category
                ? route('menus.category.show', ['slug' => $item->slug, 'category_slug' => $item->category->slug])
                : ($item->page
                ? route('menus.page.show', ['slug' => $item->slug, 'page_slug' => $item->page->slug])
                : ($item->custom_url
                ? url($item->custom_url)
                : ''));

                if (empty($url) || in_array($url, $addedUrls)) {
                continue;
                }

                $addedUrls[] = $url;
                $isActive = request()->fullUrl() == $url;
                @endphp

                <li class="{{ $isActive ? 'active' : '' }}">
                    <a href="{{ $url }}">{{ $item->name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
<!-- /NAVIGATION -->



<!-- BREADCRUMB -->
<x-breadcrumbs :breadcrumbs="$breadcrumbs" />
<!-- /BREADCRUMB -->