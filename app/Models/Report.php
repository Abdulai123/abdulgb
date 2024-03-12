<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function store(){
        return $this->belongsTo(Store::class, 'reported_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'reported_id');
    }

}

