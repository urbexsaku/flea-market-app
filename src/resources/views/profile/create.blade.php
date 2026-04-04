@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-form__content">
  <h2 class="profile-form__heading">プロフィール設定</h2>
  <form class="form">
  </form>

  <form class="form" action="{{ route('update') }}" method="post" novalidate>
    @csrf
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">ユーザー名</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="name" value="{{ old('name', auth()->user()->name }}">
        </div>
        <div class="form__error">
          @error('name')
            {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">郵便番号</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="postal_code" value="{{ old('postal_code') }}">
        </div>
        <div class="form__error">
          @error('postal_code')
            {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">住所</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="address">
        </div>
        <div class="form__error">
          @error('address')
            {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">建物名</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="building">
        </div>
        <div class="form__error">
          @error('building')
            {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="form__button">
      <button type="submit" class="form__button-submit">更新する</button>
    </div>
  </form>
</div>

@endsection