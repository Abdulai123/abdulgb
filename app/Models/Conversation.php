<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function support(){
        return $this->hasOne(Support::class);
    }

    public function participants(){
        return $this->hasMany(Participant::class);
    }

    public function dispute(){
        return $this->hasOne(Dispute::class);
    }
}
