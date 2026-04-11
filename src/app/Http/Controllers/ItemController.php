<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', '');

        if ($tab === 'like') {
            $items = Auth::user()->likedItems()->get();
        } else {
            $items = Item::all();
        }

        return view('index', compact('tab','items'));
    }
}
