@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="item-list__content">
  <nav class="item-list__tab">
    <ul class="item-list__tab-list">
      <li class="item-list__tab-item {{ $tab === 'recommend' ? 'is-active' : '' }}">
        <a href="/?tab=recommend&keyword={{ $keyword ?? '' }}">おすすめ</a>
      </li>
      <li class="item-list__tab-item {{ $tab === 'mylist' ? 'is-active' : '' }}">
        <a href="/?tab=mylist&keyword={{ $keyword ?? '' }}">マイリスト</a>
      </li>
    </ul>
  </nav>

  <div class="item-list__grid">
    @foreach ($items as $item)
    <div class="item-list__card">
      <a href="/item/{{ $item->id }}">
        <div class="item-list__image-wrap">
          <img class="item-list__image" src="{{ asset('storage/' . $item->image) }}">
          @if ($item->is_sold)
          <span class="item-list__sold">Sold</span>
          @endif
        </div>
      </a>
      <p class="item-list__name">{{ $item->name }}</p>
    </div>
    @endforeach
  </div>
</div>
@endsection