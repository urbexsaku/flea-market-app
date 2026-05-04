<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'price',
        'description',
        'condition',
        'image',
        'is_sold',
    ];

    public function getConditionTextAttribute()
    {
        return [
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        ][$this->condition];
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_item',
            'item_id',
            'category_id',
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy($user)
    {
        if (!$user) {
            return false;
        }

        return $this->likes() // いいね一覧
        ->where('user_id', $user->id) // ユーザーがいいねしているか
        ->exists(); // 1件でもあればtrue
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        return $query->when($keyword, function ($query, $keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        });
    }
}
