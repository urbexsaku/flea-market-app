<?php

namespace App\Http\Controllers;

use App\Models\Like;

class LikeController extends Controller
{
    public function toggle($item_id)
    {
        $user = auth()->user();

        $like = Like::where('user_id', $user->id)
        ->where('item_id', $item_id)
        ->first();

        if ($like) { //すでにいいねしている場合
            $like->delete(); //いいね削除
            $liked = false; //JSにfalse返す
        } else {
            Like::create([ //レコード作成（いいね追加）
                'user_id' => $user->id,
                'item_id' => $item_id,
            ]);
            $liked = true; //JSにtrue返す
        }

        $count = Like::where('item_id', $item_id)->count();

        return response()->json([ //JSにJSON形式で返す
            'liked' => $liked,
            'count' => $count
        ]);
    }
    
}
