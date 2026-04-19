@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address__content">
  <h2 class="address__heading">住所の変更</h2>
  <form class="form" action="/purchase/address/{{ $item->id }}" method="post">
    @csrf
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label">郵便番号</span>
      </div>
      <div class="form__group-content">
        <div class="form__input">
          <input type="text" name="postal_code" value="{{ old('postal_code', $deliveryAddress['postal_code']) }}">
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
        <span class="form__label">住所</span>
      </div>
      <div class="form__group-content">
        <div class="form__input">
          <input type="text" name="address" value="{{ old('address', $deliveryAddress['address']) }}">
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
        <span class="form__label">建物名</span>
      </div>
      <div class="form__group-content">
        <div class="form__input">
          <input type="text" name="building" value="{{ old('building', $deliveryAddress['building']) }}">
        </div>
      </div>
    </div>

    <div class="form__button">
      <button type="submit" class="form__button-submit">更新する</button>
    </div>
  </form>
</div>

@endsection