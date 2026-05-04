@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibition.css') }}">
@endsection

@section('content')
<div class="sell-page__content">
  <h1 class="sell-page__heading">商品の出品</h1>
  <form class="sell-form" action="/sell" method="post" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="sell-form__image">
      <h2 class="sell-form__image-heading">商品画像</h2>
      <div class="sell-form__image-area">
        <img id="imagePreview" class="sell-form__image-preview" src="" style="display: none;">
        <label class="sell-form__image-button" for="imageInput">画像を選択する</label>
        <input type="file" name="image" id="imageInput" accept="image/*" hidden>
      </div>
      <div class="sell-form__error">
        @error('image')
        {{ $message }}
        @enderror
      </div>
    </div>

    <div class="sell-form__detail">
      <h2 class="sell-form__detail-heading">商品の詳細</h2>
      <div class="sell-form__categories">
        <div class="sell-form__categories-title">カテゴリー</div>
        <div class="sell-form__categories-items">
          @foreach ($categories as $category)
          <label class="sell-form__categories-item">
            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="sell-form__category-input" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
            <span class="sell-form__category-label">{{ $category->content }}</span>
          </label>
          @endforeach
        </div>
        <div class="sell-form__error">
          @error('categories')
          {{ $message }}
          @enderror
        </div>
      </div>
      <div class="sell-form__condition">
        <div class="sell-form__condition-title">商品の状態</div>
        <select name="condition" class="sell-form__condition-select">
          <option value="">選択してください</option>
          <option value="1" {{ old('condition') == 1 ? 'selected' : '' }}>良好</option>
          <option value="2" {{ old('condition') == 2 ? 'selected' : '' }}>目立った傷や汚れなし</option>
          <option value="3" {{ old('condition') == 3 ? 'selected' : '' }}>やや傷や汚れあり</option>
          <option value="4" {{ old('condition') == 4 ? 'selected' : '' }}>状態が悪い</option>
        </select>
        <div class="sell-form__error">
          @error('condition')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="sell-form__info">
      <h2 class="sell-form__detail-heading">商品名と説明</h2>
      <div class="sell-form__group">
        <div class="sell-form__group-title">
          <span class="sell-form__label">商品名</span>
        </div>
        <div class="sell-form__group-content">
          <div class="sell-form__input">
            <input type="text" name="name" value="{{ old('name') }}">
          </div>
          <div class="sell-form__error">
            @error('name')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>

      <div class="sell-form__group">
        <div class="sell-form__group-title">
          <span class="sell-form__label">ブランド名</span>
        </div>
        <div class="sell-form__group-content">
          <div class="sell-form__input">
            <input type="text" name="brand" value="{{ old('brand') }}">
          </div>
        </div>
      </div>

      <div class="sell-form__group">
        <div class="sell-form__group-title">
          <span class="sell-form__label">商品の説明</span>
        </div>
        <div class="sell-form__group-content">
          <div class="sell-form__input">
            <textarea name="description">{{ old('description') }}</textarea>
          </div>
          <div class="sell-form__error">
            @error('description')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>

      <div class="sell-form__group">
        <div class="sell-form__group-title">
          <span class="sell-form__label">販売価格</span>
        </div>
        <div class="sell-form__group-content">
          <div class="sell-form__input sell-form__input--price">
            <span class="sell-form__price-mark">￥</span>
            <input type="text" name="price" value="{{ old('price') }}">
          </div>
          <div class="sell-form__error">
            @error('price')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>

      <div class="sell-form__button">
        <button type="submit" class="sell-form__button-submit">出品する</button>
      </div>
    </div>
  </form>
</div>

<script>
  const imageInput = document.getElementById('imageInput');
  const imagePreview = document.getElementById('imagePreview');
  const imageArea = document.querySelector('.sell-form__image-area');

  imageInput.addEventListener('change', function(e) {
    const file = e.target.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = function(event) {
      imagePreview.src = event.target.result;
      imagePreview.style.display = 'block';
      imageArea.classList.add('is-active');
    };

    reader.readAsDataURL(file);
  });
</script>
@endsection