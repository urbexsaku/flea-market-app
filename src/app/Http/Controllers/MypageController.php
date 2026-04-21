<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell'); //sellタブをデフォルトに設定

        if ($page === 'buy') { //buyタブが選択されている場合
            $items = $user->purchasedItems()->get(); //purchasesテーブルを経由してユーザーidが購入した商品を取得
        } else { //sellタブが選択されている場合
            $items = Item::where('user_id', $user->id)->get(); //itemsテーブルからユーザーidが出品した商品を取得
        }

        return view('mypage', compact('user', 'page', 'items'));
    }
}
