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

        $deliveryAddress = session('delivery_address', [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('purchase', compact('item', 'deliveryAddress'));
    }

    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $deliveryAddress = session('delivery_address', [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('address', compact('item', 'deliveryAddress'));
    }

    public function update(AddressRequest $request, $item_id)
    {
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

        session()->forget('delivery_address');

        return redirect('/');
    }

    public function checkout(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        session([
            'purchase_data' => [
                'payment_method' => $request->payment_method,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ],
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
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
            'success_url' => url('/purchase/success?session_id={CHECKOUT_SESSION_ID}&item_id=' . $item->id),
            'cancel_url' => url('/purchase/' . $item->id),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $item = Item::findOrFail($request->item_id);

        if ($item->is_sold) {
            return redirect('/');
        }

        $purchaseData = session('purchase_data');

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_method' => $purchaseData['payment_method'],
            'postal_code' => $purchaseData['postal_code'],
            'address' => $purchaseData['address'],
            'building' => $purchaseData['building'],
        ]);

        $item->update([
            'is_sold' => true,
        ]);

        session()->forget('delivery_address');
        session()->forget('purchase_data');

        return redirect('/');
    }
}
