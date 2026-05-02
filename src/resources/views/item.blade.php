@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item-detail__content">
  <div class="item-detail">
    <div class="item-detail__image">
      <img src="{{ asset('storage/' . $item->image) }}">
    </div>

    <div class="item-detail__info">
      <h1 class="item-detail__name">{{ $item->name }}</h1>
      <p class="item-detail__brand">{{ $item->brand }}</p>

      <p class="item-detail__price">
        ¥{{ number_format($item->price) }}
        <span class="item-detail__price-tax">(税込)</span>
      </p>

      <div class="item-detail__actions">
        <div class="item-detail__action">
          @auth
          <button type="button" id="likeButton" class="item-detail__action-button" data-item-id="{{ $item->id }}">
            <img
              id="heart"
              src="{{ $item->isLikedBy(auth()->user()) ? asset('images/heart-logo-pink.png') : asset('images/heart-logo-default.png') }}"
              class="item-detail__action-icon"> <!--ユーザーがいいねしていればピンク、していなければ白を表示-->
          </button>
          @else
          <a href="/login">
            <img class="item-detail__action-icon" src="{{ asset('images/heart-logo-default.png') }}"> <!-- 未ログインであればログインぺージリンク表示 -->
          </a>
          @endauth
          <span id="likeCount">{{ $item->likes->count() }}</span>
        </div>

        <div class="item-detail__action">
          <img src="{{ asset('images/speech-bubble.png') }}" class="item-detail__action-icon">
          <span data-testid="comment-count-top">{{ $item->comments->count() }}</span>
        </div>
      </div>

      @if ($item->is_sold)
      <div class="item-detail__soldout">売り切れ</div>
      @else
      <div class="item-detail__purchase">
        <a class="item-detail__purchase-link" href="/purchase/{{ $item->id }}">購入手続きへ</a>
      </div>
      @endif

      <h2 class="item-detail__heading">商品説明</h2>
      <p class="item-detail__description">{{ $item->description }}</p>

      <h2 class="item-detail__heading">商品の情報</h2>

      <div class="item-detail__info-row">
        <div class="item-detail__label">カテゴリー</div>
        <div class="item-detail__category">
          @foreach ($item->categories as $category)
          <span class="item-detail__category-tag"> {{ $category->content }}</span>
          @endforeach
        </div>
      </div>
      <div class="item-detail__info-row">
        <div class="item-detail__label">商品の状態</div>
        <div class="item-detail__text">{{ $item->condition_text }}</div>
      </div>

      <div class="item-detail__comment">
        <h2 class="item-detail__comment-heading" data-testid="comment-count-bottom">コメント ({{ $item->comments->count() }})</h2>

        @foreach ($item->comments as $comment)
        <div class="item-detail__comment-item">
          <div class="item-detail__comment-user">
            @if ($comment->user->profile_image)
            <img class="item-detail__comment-user-icon" src="{{ asset('storage/' . $comment->user->profile_image) }}">
            @else
            <div class="item-detail__comment-user-icon--default"></div>
            @endif

            <p class="item-detail__comment-user-name">{{ $comment->user->name }}</p>
          </div>
          <p class="item-detail__comment-text">{{ $comment->content }}</p>
        </div>
        @endforeach

        <div class="item-detail__comment-form-wrap">
          <h2 class="item-detail__comment-form-heading">商品へのコメント</h2>
          <form action="/comment/{{ $item->id }}" method="post" class="item-detail__comment-form">
            @csrf
            <div class="item-detail__comment-form-group">
              <textarea name="content" class="item-detail__comment-textarea"></textarea>
            </div>

            @error('content')
            <p class="item-detail__comment-error">{{ $message }}</p>
            @enderror

            <button type="submit" class="item-detail__comment-button">コメントを送信する</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const button = document.getElementById('likeButton'); //id=likeButtonの要素取得
  const heart = document.getElementById('heart'); //id=heartの要素取得
  const count = document.getElementById('likeCount'); //id=likeCountの要素取得

  if (button) {
    button.addEventListener('click', async () => { //likeButtonクリック時に実行
      const itemId = button.dataset.itemId; //data-item-idからitem id取得

      const response = await fetch(`/like/${itemId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (data.liked) { //LikeControllerから返ってきたlikedがtrueなら
        heart.src = "{{ asset('images/heart-logo-pink.png') }}";
      } else {
        heart.src = "{{ asset('images/heart-logo-default.png') }}";
      }

      count.textContent = data.count;
    });
  }
</script>
@endsection