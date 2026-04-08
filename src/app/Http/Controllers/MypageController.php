<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $items = Auth::user()->purchasedItems()->get();
        } else {
            $items = Item::where('user_id', Auth::id())->get();
        }

        return view('mypage', compact('page','items'));
    }
}
