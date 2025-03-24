<!-- TOP HEADER -->
@props(['contacts'])

<div id="top-header">
    <div class="container">
        <ul class="header-links pull-left">
            <li><a href="#"><i class="fa fa-phone"></i> {{ $contacts->phone ?? '+021-95-51-84' }}</a></li>
            <li><a href="#"><i class="fa fa-envelope-o"></i> {{ $contacts->email ?? 'email@email.com' }}</a></li>
            <li><a href="#"><i class="fa fa-map-marker"></i> {{ $contacts->address ?? '1734 Stonecoal Road' }}</a></li>
        </ul>
        <ul class="header-links pull-right">
            <li><a href="#"><i class="fa fa-dollar"></i> USD</a></li>
            <li><a href="{{ route('my-account') }}"><i class="fa fa-user-o"></i> My Account</a></li>
        </ul>
    </div>
</div>
<!-- /TOP HEADER -->