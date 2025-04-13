<!-- Сообщения об ошибках -->
@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Сообщение об успехе -->
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif