@props(['footerSocialLinks'])
{{-- временная проверка --}}
{{-- <pre>
{{ var_dump($footerSocialLinks) }}
</pre> --}}
<!-- Newsletter Form -->
<div id="newsletter" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="newsletter">
                    <p>Sign Up for the <strong>NEWSLETTER</strong></p>
                    <form action="{{ route('subscribe') }}" method="POST">
                        @csrf
                        <input class="input" type="email" name="email" placeholder="Enter Your Email" required>
                        <button class="newsletter-btn"><i class="fa fa-envelope"></i> Subscribe</button>
                    </form>
                    @if(session('message'))
                    <p class="alert {{ session('message_class') }}">{{ session('message') }}</p>
                    @endif
                    <ul class="newsletter-follow">
                        {{-- <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest"></i></a></li> --}}
                        @foreach($footerSocialLinks as $link)
                        <li>
                            <a href="{{ $link->url }}" @if($link->open_in_new_tab) target="_blank" @endif title="{{ $link->name }}">
                                <i class="{{ $link->icon_class }}"></i>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>