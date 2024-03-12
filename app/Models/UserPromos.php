<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPromos extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'promocode_id',
    ];

    public function promocode(){
        return $this->belongsTo(Promocode::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cart(){
        return $this->belongsTo(Cart::class);
    }
}
