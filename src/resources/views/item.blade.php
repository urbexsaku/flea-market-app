@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item__content">
  <div class="item-detail">
    <div class="item-detail__image">
      <img src=" {{ asset('storage/' . $item->image) }}">
    </div>
    <div class="item-detail__info">
      <h1 class="item-name">{{ $item->name }}</h1>
      <p class="item-brand">{{ $item->brand }}</p>
      <p class="item-price">
        ¥{{ number_format($item->price) }}
        <span class="item-price__tax">(税込)</span>
      </p>

      <div class="item-actions">
        <div class="action-item">
          @auth
          <button type="button" id="likeButton" data-item-id="{{ $item->id }}">
            <img
              id="heart"
              src="{{ $item->isLikedBy(auth()->user()) ? asset('images/heart-logo-pink.png') : asset('images/heart-logo-default.png') }}"
              class="action-icon">
          </button>
          @else
          <a href="/login">
            <img class="action-icon" src="{{ asset('images/heart-logo-default.png') }}">
          </a>
          @endauth

          <span id="likeCount">{{ $item->likes->count() }}</span>
        </div>
        <div class="action-item">
          <img src="{{ asset('images/speech-bubble.png') }}" class="action-icon">
          <span>{{ $item->comments->count() }}</span>
        </div>
      </div>
      <h2>商品説明</h2>
      <p>{{ $item->description }}</p>
      <h2>商品の情報</h2>
      <div class="item-detail__info-items">
        <p class="item-detail__label">カテゴリー</p>
        <div class="item-detail__category"></div>
        <p class="item-detail__label">商品の状態</p>
        {{ $item->condition_text }}
      </div>

    </div>
  </div>
</div>

<script>
  const button = document.getElementById('likeButton'); //id=likeButtonの要素取得
  const heart = document.getElementById('heart'); //id=heartの要素取得
  const count = document.getElementById('likeCount'); //id=likeCountの要素取得

  button.addEventListener('click', async () => { //likeButtonクリック時に実行
    const itemId = button.dataset.itemId; //data-item-idからitem id取得

    const response = await fetch(`/like/${itemId}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    const data = await response.json();

    if (data.liked) { //LikeControllerから返ってきたlikedがtrueなら
      heart.src = 'images/heart-logo-pink.png';
    } else {
      heart.src = 'images/heart-logo-default.png';
    }

    count.textContent = data.count;
  });
</script>
@endsection