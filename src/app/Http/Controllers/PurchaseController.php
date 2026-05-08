<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function index($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $deliveryAddress = $this->getDeliveryAddress($user);

        return view('purchase', compact('item', 'deliveryAddress'));
    }

    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $deliveryAddress = $this->getDeliveryAddress($user);

        return view('address', compact('item', 'deliveryAddress'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        // 住所変更後にセッションへ保存
        session([
            'delivery_address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);

        return redirect("/purchase/{$item_id}");
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        // 商品が購入済みの場合、商品一覧へリダイレクト
        if ($item->is_sold) {
            return redirect('/');
        }

        $deliveryAddress = $this->getDeliveryAddress($user);

        // 購入情報保存
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
            'postal_code' => $deliveryAddress['postal_code'],
            'address' => $deliveryAddress['address'],
            'building' => $deliveryAddress['building'],
        ]);

        $item->update([
            'is_sold' => true,
        ]);

        session()->forget('delivery_address');

        // Stripe認証
        Stripe::setApiKey(config('services.stripe.secret'));

        // 決済セッション作成
        $session = Session::create([ 
            'payment_method_types' => ['card', 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/'),
            'cancel_url' => url('/purchase/' . $item->id),
        ]);

        return redirect($session->url);
    }

    private function getDeliveryAddress($user)
    {
        // 住所変更あればセッションの配送先を取得、なければユーザー登録住所を取得
        return session('delivery_address', [ 
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);
    }
}
