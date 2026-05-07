<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        if ($item->is_sold) {
            return redirect('/');
        }

        session([
            'payment_method' => $request->payment_method,
        ]);

        // PHPUnit用
        if (app()->environment('testing')) {

            $deliveryAddress = $this->getDeliveryAddress($user);

            Purchase::create([
                'user_id' => auth()->id(),
                'item_id' => $item->id,
                'payment_method' => session('payment_method'),
                'postal_code' => $deliveryAddress['postal_code'],
                'address' => $deliveryAddress['address'],
                'building' => $deliveryAddress['building'],
            ]);

            $item->update([
                'is_sold' => true,
            ]);

            session()->forget([
                'delivery_address',
                'payment_method',
            ]);

            return redirect('/');
        }

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
            'success_url' => url('/purchase/success?session_id={CHECKOUT_SESSION_ID}&item_id=' . $item->id),
            'cancel_url' => url('/purchase/' . $item->id),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        $user = auth()->user();

        if ($item->is_sold) {
            return redirect('/');
        }

        $deliveryAddress = $this->getDeliveryAddress($user);

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_method' => session('payment_method'),
            'postal_code' => $deliveryAddress['postal_code'],
            'address' => $deliveryAddress['address'],
            'building' => $deliveryAddress['building'],
        ]);

        $item->update([
            'is_sold' => true,
        ]);

        session()->forget([
            'delivery_address',
            'payment_method',
        ]);

        return redirect('/');
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
