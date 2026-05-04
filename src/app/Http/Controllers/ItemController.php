<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->tab ?? 'recommend'; // デフォルトタブをrecommendに設定（null=recommend）
        $keyword = $request->keyword;

        if ($tab === 'mylist') { // マイリストタブの設定
            if (auth()->check()) {
                $query = auth()->user()->likedItems(); // ログイン済ならlikedItems取得
            } else {
                $query = Item::whereRaw('0=1')->with('categories'); // 未ログインなら空を返すダミークエリ
            }
        } else {
            if (auth()->check()) {
                $query = Item::where('user_id', '!=', auth()->id())->with('categories'); // ログイン済みならログインユーザーが出品した商品を除外
            } else {
                $query = Item::query()->with('categories'); // 未ログインならすべて表示
            }
        }

        if (!empty($keyword)) {
            $query->keywordSearch($keyword); // 検索機能
        }

        $items = $query->get();

        return view('index', compact('tab', 'keyword', 'items'));
    }

    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('item', compact('item'));
    }
}
