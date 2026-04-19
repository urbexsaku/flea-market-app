<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
        'postal_code',
        'address',
        'building',
    ];

    public function getPaymentMethodTextAttribute()
    {
        return [
            1 => 'コンビニ支払い',
            2 => 'カード支払い',
        ][$this->payment_method];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }
}
