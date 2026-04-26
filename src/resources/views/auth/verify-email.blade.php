@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify">
  <div class="verify__content">
    <p class="verify__message">登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p class="verify__message">メール認証を完了してください。</p>

    @if (session('message'))
     <p class="verify__notice">{{ session('message') }}</p>
    @endif

    <form class="verify__resend" action="{{ route('verification.send') }}" method="post">
      @csrf
      <button class="verify__resend-button" type="submit">認証メールを再送する</button>
    </form>
  </div>
</div>
@endsection