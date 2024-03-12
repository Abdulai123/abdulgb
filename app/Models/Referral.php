<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    public function referredUser(){
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
