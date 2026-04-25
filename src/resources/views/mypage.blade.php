@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage__content">
  <div class="mypage__menu">
    @if ($user->profile_image)
      <img 
        class="mypage__image"
        src="{{ asset('storage/' . $user->profile_image) }}">
    @else
      <div class="mypage__image-placeholder"></div>
    @endif
    <span class="mypage__menu-item">{{ $user->name }}</span>
    <a class="mypage__menu-link" href="/mypage/profile">プロフィールを編集</a>
  </div>
  <div class="mypage__list">
    <nav class="mypage__tab">
      <ul class="mypage__tab-list">
        <li class="mypage__tab-item {{ $page === 'sell' ? 'is-active' : '' }}">
          <a href="/mypage?page=sell">出品した商品</a>
        </li>
        <li class="mypage__tab-item {{ $page === 'buy' ? 'is-active' : '' }}">
          <a href="/mypage?page=buy">購入した商品</a>
        </li>
      </ul>
    </nav>
    

    <div class="mypage__item-list">
      @foreach ($items as $item)
      <div class="mypage__item">
        <img class="mypage__item-image" src="{{ asset('storage/' . $item->image) }}">
        <p class="mypage__item-name">{{ $item->name }}</p>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection