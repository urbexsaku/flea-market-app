@extends('layouts.auth')

@section('content')
<div class="auth-form__content">
  <h1 class="auth-form__heading">会員登録</h1>
  <form class="form" action="{{ route('register') }}" method="post" novalidate>
    @csrf
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label">ユーザー名</span>
      </div>
      <div class="form__group-content">
        <div class="form__input">
          <input type="text" name="name" value="{{ old('name') }}">
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
        <span class="form__label">メールアドレス</span>
      </div>
      <div class="form__group-content">
        <div class="form__input">
          <input type="email" name="email" value="{{ old('email') }}">
        </div>
        <div class="form__error">
          @error('email')
            {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label">パスワード</span>
      </div>
      <div class="form__group-content">
        <div class="form__input">
          <input type="password" name="password">
        </div>
        <div class="form__error">
          @error('password')
            {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label">確認用パスワード</span>
      </div>
      <div class="form__group-content">
        <div class="form__input">
          <input type="password" name="password_confirmation">
        </div>
        <div class="form__error">
          @error('password')
            {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="form__button">
      <button type="submit" class="form__button-submit">登録する</button>
    </div>
    <a class="form__link" href="/login">ログインはこちら</a>
  </form>
</div>

@endsection