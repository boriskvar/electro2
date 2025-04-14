<ul class="product-share-icons">
    @foreach($productSocialLinks as $link)
    <li>
        <a href="{{ $link->url }}" @if($link->open_in_new_tab) target="_blank" @endif title="{{ $link->name }}">
            <i class="{{ $link->icon_class }}"></i>
        </a>
    </li>
    @endforeach
</ul>