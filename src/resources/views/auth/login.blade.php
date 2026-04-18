@extends('layouts.auth')

@section('content')
<div class="auth-form__content">
  <h2 class="auth-form__heading">ログイン</h2>
  <form class="form" action="{{ route('login') }}" method="post" novalidate>
    @csrf
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

    <div class="form__button">
      <button type="submit" class="form__button-submit">ログインする</button>
    </div>
    <a class="form__link" href="/register">会員登録はこちら</a>
  </form>
</div>

@endsection