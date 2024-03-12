<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function parentProducts(){
        return $this->hasMany(Product::class, 'parent_category_id');
    }

    public function subProducts(){
        return $this->hasMany(Product::class, 'sub_category_id');
    }
}
