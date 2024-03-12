<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff(){
        return $this->belongsTo(User::class, 'staff_id');
    }
}
