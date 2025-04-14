<div class="form-group">
    <label for="name">Название:</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $socialLink->name ?? '') }}" required>
    @error('name')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="url">URL:</label>
    <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $socialLink->url ?? '') }}" required>
    @error('url')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="icon_class">CSS класс иконки:</label>
    <input type="text" class="form-control @error('icon_class') is-invalid @enderror" id="icon_class" name="icon_class" value="{{ old('icon_class', $socialLink->icon_class ?? '') }}">
    @error('icon_class')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

{{-- <div class="form-group">
    <label for="type">Тип:</label>
    <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type', $socialLink->type ?? '') }}">
@error('type')
<span class="text-danger">{{ $message }}</span>
@enderror
</div> --}}
<div class="form-group">
    <label for="type">Тип:</label>
    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type">
        <option value="">-- Выберите тип --</option>
        <option value="footer" {{ old('type', $socialLink->type ?? '') == 'footer' ? 'selected' : '' }}>Footer</option>
        <option value="product" {{ old('type', $socialLink->type ?? '') == 'product' ? 'selected' : '' }}>Product</option>
        {{-- <option value="header" {{ old('type', $socialLink->type ?? '') == 'header' ? 'selected' : '' }}>Header</option> --}}
    </select>
    @error('type')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

{{-- <div class="form-group">
    <label for="type">Тип ссылки:</label>
    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type">
        <option value="">-- Выберите тип --</option>
        <option value="external" {{ old('type', $socialLink->type ?? '') == 'external' ? 'selected' : '' }}>Внешняя ссылка</option>
<option value="email" {{ old('type', $socialLink->type ?? '') == 'email' ? 'selected' : '' }}>Email</option>
<option value="facebook" {{ old('type', $socialLink->type ?? '') == 'facebook' ? 'selected' : '' }}>Facebook</option>
<option value="google" {{ old('type', $socialLink->type ?? '') == 'google' ? 'selected' : '' }}>Googleplus</option>
<option value="twitter" {{ old('type', $socialLink->type ?? '') == 'twitter' ? 'selected' : '' }}>Twitter</option>
<option value="instagram" {{ old('type', $socialLink->type ?? '') == 'instagram' ? 'selected' : '' }}>Instagram</option>
<option value="telegram" {{ old('type', $socialLink->type ?? '') == 'telegram' ? 'selected' : '' }}>Telegram</option>
<option value="whatsapp" {{ old('type', $socialLink->type ?? '') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
<option value="viber" {{ old('type', $socialLink->type ?? '') == 'viber' ? 'selected' : '' }}>Viber</option>
<option value="pinterest" {{ old('type', $socialLink->type ?? '') == 'pinterest' ? 'selected' : '' }}>Pinterest</option>
<!-- Добавь любые другие типы, если нужно -->
</select>
@error('type')
<span class="text-danger">{{ $message }}</span>
@enderror
</div> --}}


<div class="form-group">
    <label for="position">Позиция:</label>
    <input type="number" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $socialLink->position ?? 0) }}">
    @error('position')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="checkbox">
    <label>
        <input type="checkbox" name="open_in_new_tab" value="1" {{ old('open_in_new_tab', $socialLink->open_in_new_tab ?? false) ? 'checked' : '' }}>
        Открывать в новой вкладке
    </label>
</div>

<div class="checkbox">
    <label>
        <input type="checkbox" name="active" value="1" {{ old('active', $socialLink->active ?? true) ? 'checked' : '' }}>
        Активна
    </label>
</div>