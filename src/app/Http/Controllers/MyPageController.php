<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'sell');

        if ($tab === 'buy') {
            $items = Auth::user()->purchasedItems()->get();
        } else {
            $items = Item::where('user_id', Auth::id())->get();
        }

        return view('mypage', compact('tab','items'));
    }
}
