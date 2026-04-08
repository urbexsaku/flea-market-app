<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', '');

        if ($tab === 'like') {
            $items = Auth::user()->likedItems()->get();
        } else {
            $items = Item::where('user_id', Auth::id())->get();
        }

        return view('index', compact('tab','items'));
    }
}
