<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;

class ExhibitionController extends Controller
{
    public function index() {
        $categories = Category::all();

        return view ('exhibition', compact('categories'));
    }

    public function store(ExhibitionRequest $request) {
        $path = $request->file('image')->store('images', 'public');
                
        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'price' => $request->price,
            'description' => $request->description,
            'condition' => $request->condition,
            'image' => $path,            
        ]);

        $item->categories()->attach($request->categories);

        return redirect ('/');
    }
}
