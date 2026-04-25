@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-form__content">
  <h1 class="profile-form__heading">プロフィール設定</h1>
  <form class="profile-form" action="/mypage/profile" method="post" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="profile-form__image">
      <img
        class="profile-form__image-preview"
        id="imagePreview"
        src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : '' }}"
        style="{{ $user->profile_image ? 'display:block' : 'display:none;' }}">
      <div
        class="profile-form__image-placeholder"
        id="imagePlaceholder"
        style="{{ $user->profile_image ? 'display:none' : 'display:block' }}"></div>

      <div class="profile-form__image-control">
        <label class="profile-form__image-button" for="imageInput">画像を選択する</label>
        <input type="file" name="profile_image" id="imageInput" accept="image/*" hidden>
        <div class="profile-form__error">
          @error('profile_image')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="profile-form__group">
      <div class="profile-form__group-title">
        <span class="profile-form__label">ユーザー名</span>
      </div>
      <div class="profile-form__group-content">
        <div class="profile-form__input">
          <input type="text" name="name" value="{{ old('name', $user->name) }}">
        </div>
        <div class="profile-form__error">
          @error('name')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="profile-form__group">
      <div class="profile-form__group-title">
        <span class="profile-form__label">郵便番号</span>
      </div>
      <div class="profile-form__group-content">
        <div class="profile-form__input">
          <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
        </div>
        <div class="profile-form__error">
          @error('postal_code')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="profile-form__group">
      <div class="profile-form__group-title">
        <span class="profile-form__label">住所</span>
      </div>
      <div class="profile-form__group-content">
        <div class="profile-form__input">
          <input type="text" name="address" value="{{ old('address', $user->address) }}">
        </div>
        <div class="profile-form__error">
          @error('address')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="profile-form__group">
      <div class="profile-form__group-title">
        <span class="profile-form__label">建物名</span>
      </div>
      <div class="profile-form__group-content">
        <div class="profile-form__input">
          <input type="text" name="building" value="{{ old('building', $user->building) }}">
        </div>
      </div>
    </div>

    <div class="profile-form__button">
      <button type="submit" class="profile-form__button-submit">更新する</button>
    </div>
  </form>
</div>

<script>
  const imageInput = document.getElementById('imageInput');
  const imagePreview = document.getElementById('imagePreview');
  const imagePlaceholder = document.getElementById('imagePlaceholder');

  imageInput.addEventListener('change', function(e) {
    const file = e.target.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = function(event) {
      imagePreview.src = event.target.result;
      imagePreview.style.display = 'block';
      imagePlaceholder.style.display = 'none';
    };

    reader.readAsDataURL(file);
  });
</script>
@endsection