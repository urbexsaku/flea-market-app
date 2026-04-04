<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        if (!$user->profile_completed){
            return view('profile.create');
        }

        return view('profile.edit');
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        $user->update([
            'name' => $requst->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect('/mypage');
    }

}
