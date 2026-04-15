@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="item__content">
  <div class="item__list">
    <nav class="item__tab">
      <ul class="item__tab-list">
        <li class="item__tab-item {{ $tab === 'recommend' ? 'is-active' : '' }}">
          <a href="/?tab=recommend&keyword={{ $keyword ?? '' }}">おすすめ</a>
        </li>
        <li class="item__tab-item {{ $tab === 'mylist' ? 'is-active' : '' }}">
          <a href="/?tab=mylist&keyword={{ $keyword ?? '' }}">マイリスト</a>
        </li>
      </ul>
    </nav>

    <div class="item__item-list">
      @foreach ($items as $item)
      <div class="item__item">
        <a href="/item/{{ $item->id }}">
          <img class="item__item-image" src="{{ asset('storage/' . $item->image) }}">
        </a>
        <p class="item__item-name">{{ $item->name }}</p>
      </div>
      @endforeach
    </div>
  </div>
  @endsection