<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'extra_option_id',
        'note',
    ];
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function extraShipping(){
        return $this->belongsTo(ExtraOption::class, 'extra_option_id');
    }

    public function cartUsedPromo(){
        return $this->hasOne(UserPromos::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
