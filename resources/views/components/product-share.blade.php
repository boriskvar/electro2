@if($productSocialLinks->count())
<ul class="product-links">
    <li>Share:</li>
    @foreach($productSocialLinks as $link)
    <li>
        @if($link->type === 'email')
        <a href="mailto:?subject=Посмотри этот товар&body=Мне кажется, тебе это понравится: {{ url()->current() }}" title="{{ $link->name }}">
            <i class="{{ $link->icon_class }}"></i>
        </a>
        @else
        <a href="{{ $link->url }}" @if($link->open_in_new_tab) target="_blank" @endif
            title="{{ $link->name }}">
            <i class="{{ $link->icon_class }}"></i>
        </a>
        @endif
    </li>
    @endforeach
</ul>
@endif