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

        $deliveryAddress = session('delivery_address', [ //住所変更ある場合、変更した住所をセッションから取得
            'postal_code' => $user->postal_code, //住所変更ない場合、ユーザー登録住所を取得
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('purchase', compact('item', 'deliveryAddress'));
    }

    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $deliveryAddress = session('delivery_address', [ //住所変更ある場合、変更した住所をセッションから取得
            'postal_code' => $user->postal_code, //住所変更ない場合、ユーザー登録住所を取得
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('address', compact('item', 'deliveryAddress'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        session([
            'delivery_address' => [ //住所変更した場合、セッションに保存
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

        if ($item->is_sold) {
            return redirect('/');
        }
            
        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'payment_method' => $request->payment_method,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        $item->update([
            'is_sold' => true,
        ]);

        session()->forget('delivery_address'); //セッション削除

        return redirect('/');
    }

    public function checkout(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->is_sold) {
            return redirect('/');
        }

        Stripe::setApiKey(config('services.stripe.secret')); //Stripe認証

        $session = Session::create([ //決済セッション作成
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1
            ]],
            'mode' => 'payment',
            'success_url' => url('/purchase/success?session_id={CHECKOUT_SESSION_ID}&item_id=' . $item->id), //支払い成功時リダイレクト先
            'cancel_url' => url('/purchase/' . $item->id), //キャンセル時リダイレクト先
        ]);

        return redirect($session->url); //Stripe決済画面へ遷移
    }

    public function success(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        $user = auth()->user();

        if ($item->is_sold) {
            return redirect('/');
        }

        $deliveryAddress = session('delivery_address', [ //住所変更ある場合、変更した住所をセッションから取得
            'postal_code' => $user->postal_code, //住所変更ない場合、ユーザー登録住所を取得
            'address' => $user->address,
            'building' => $user->building,
        ]);

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_method' => 2,
            'postal_code' => $deliveryAddress['postal_code'],
            'address' => $deliveryAddress['address'],
            'building' => $deliveryAddress['building'],
        ]);

        $item->update([
            'is_sold' => true,
        ]);

        session()->forget('delivery_address');

        return redirect('/');
    }
}
