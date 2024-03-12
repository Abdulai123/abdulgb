<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }

    public function escrow(){
        return $this->belongsTo(Escrow::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function moderator(){
        return $this->belongsTo(User::class, 'mediator_id');
    }
}
