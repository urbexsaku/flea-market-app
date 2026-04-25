@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address__content">
  <h1 class="address__heading">住所の変更</h1>
  <form class="address-form" action="/purchase/address/{{ $item->id }}" method="post">
    @csrf
    <div class="address-form__group">
      <div class="address-form__group-title">
        <span class="address-form__label">郵便番号</span>
      </div>
      <div class="address-form__group-content">
        <div class="address-form__input">
          <input type="text" name="postal_code" value="{{ old('postal_code', $deliveryAddress['postal_code']) }}">
        </div>
        <div class="address-form__error">
          @error('postal_code')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="address-form__group">
      <div class="address-form__group-title">
        <span class="address-form__label">住所</span>
      </div>
      <div class="address-form__group-content">
        <div class="address-form__input">
          <input type="text" name="address" value="{{ old('address', $deliveryAddress['address']) }}">
        </div>
        <div class="address-form__error">
          @error('address')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="address-form__group">
      <div class="address-form__group-title">
        <span class="address-form__label">建物名</span>
      </div>
      <div class="address-form__group-content">
        <div class="address-form__input">
          <input type="text" name="building" value="{{ old('building', $deliveryAddress['building']) }}">
        </div>
      </div>
    </div>

    <div class="address-form__button">
      <button type="submit" class="address-form__button-submit">更新する</button>
    </div>
  </form>
</div>

@endsection