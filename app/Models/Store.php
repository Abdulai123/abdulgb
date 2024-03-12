<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function messages(){
        return $this->hasMany(Message::class, 'user_id', 'user_id');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    public function supports(){
        return $this->hasMany(Support::class, 'user_id');
    }

    public function Storeblocked(){
        return $this->hasMany(BlockStore::class);
    }

    public function StoreFavorited(){
        return $this->hasMany(FavoriteStore::class);
    }

    public function storeReports(){
        return $this->hasMany(Report::class, 'reported_id')->where('is_store', 1);
    }

    public function coupons(){
        return $this->hasMany(Promocode::class);
    }

    public function share(){
        return $this->hasMany(ShareAccess::class);
    }
}
