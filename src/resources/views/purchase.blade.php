@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form id="purchaseForm" class="purchase" action="/purchase/{{ $item->id }}" method="post">
  @csrf
  <div class="purchase__content">
    <div class="purchase__main">
      <div class="purchase-item">
        <div class="purchase-item__image">
          <img src="{{ asset('storage/' . $item->image) }}">
        </div>
        <div class="purchase-item__info">
          <h2 class="purchase-item__name">{{ $item->name }}</h2>
          <p class="purchase-item__price">¥ {{ number_format($item->price) }}</p>
        </div>
      </div>

      <div class="purchase-section__payment">
        <h3 class="purchase-section__title">支払い方法</h3>
        <div class="purchase-section__payment-select">
          <select name="payment_method" id="payment">
            <option value="">選択してください</option>
            <option value="1">コンビニ支払い</option>
            <option value="2">カード支払い</option>
          </select>
        </div>
        <div class="form__error">
          @error('payment_method')
          {{ $message }}
          @enderror
        </div>
      </div>

      <div class="purchase-section__delivery">
        <div class="purchase-section__header">
          <h3 class="purchase-section__title">配送先</h3>
          <a href="/purchase/address/{{ $item->id }}" class="purchase-section__link">変更する</a>
        </div>
        <div class="purchase-section__address">
          〒 {{ $deliveryAddress['postal_code'] }}<br>
          {{ $deliveryAddress['address'] }} {{ $deliveryAddress['building'] }}
        </div>
        <div class="form__error">
          @error('postal_code')
          {{ $message }}
          @enderror
        </div>
        <div class="form__error">
          @error('address')
          {{ $message }}
          @enderror
        </div>

        <input type="hidden" name="postal_code" value="{{ $deliveryAddress['postal_code'] }}">
        <input type="hidden" name="address" value="{{ $deliveryAddress['address'] }}">
        <input type="hidden" name="building" value="{{ $deliveryAddress['building'] }}">
      </div>
    </div>

    <aside class="purchase-summary">
      <div class="purchase-summary__table">
        <div class="purchase-summary__row">
          <span class="purchase-summary__label">商品代金</span>
          <span class="purchase-summary__price">¥{{ number_format($item->price) }}</span>
        </div>
        <div class="purchase-summary__row">
          <span class="purchase-summary__label">支払い方法</span>
          <span class="purchase-summary__payment" id="paymentText"></span>
        </div>
      </div>
      <button type="submit" class="purchase-summary__button">購入する</button>
    </aside>
  </div>
</form>

<script>
  const form = document.getElementById('purchaseForm');
  const select = document.getElementById('payment');
  const text = document.getElementById('paymentText');

  select.addEventListener('change', () => {
    const selected = select.options[select.selectedIndex].text;
    text.textContent = selected;

    if (select.value === '2') {
      form.action = '/purchase/{{ $item->id }}/checkout'; //クレジットカード払い選択の場合、checkout（Stripe）へ
    } else {
      form.action = '/purchase/{{ $item->id }}'; //コンビニ払い選択の場合
    }
  });
</script>
@endsection