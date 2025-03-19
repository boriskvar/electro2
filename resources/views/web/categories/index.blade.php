@foreach ($categories as $category)
<div>
    <a href="{{ route('categories.show', $category->slug) }}">
        {{ $category->name }}
    </a>
</div>

@foreach ($category->children as $child)
<div style="margin-left: 20px;">
    <a href="{{ route('categories.show', $child->slug) }}">
        {{ $child->name }}
    </a>
</div>
@endforeach
@endforeach
