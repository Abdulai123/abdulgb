<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteStore extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function store(){
        return $this->belongsTo(Store::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
