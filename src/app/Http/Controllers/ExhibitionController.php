<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;

class ExhibitionController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('exhibition', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image')->store('images', 'public'); // 画像をstorage/app/public/imagesに保存
                
        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'price' => $request->price,
            'description' => $request->description,
            'condition' => $request->condition,
            'image' => $path,            
        ]);

        $item->categories()->attach($request->categories); // 中間テーブルにitemとcategoryのデータ保存

        return redirect('/');
    }
}