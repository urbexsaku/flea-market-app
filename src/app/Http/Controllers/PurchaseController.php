<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;

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
}
