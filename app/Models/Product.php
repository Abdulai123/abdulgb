<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'product_name',
        'product_description',
        'price',
        'quantity',
        'ship_from',
        'product_type',
        'payment_type',
        'ship_to',
        'parent_category_id',
        'sub_category_id',
        'return_policy',
        'auto_delivery_content',
        'image_path1',
        'image_path2',
        'image_path3',
    ];

    public function parentCategory(){
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function subCategory(){
        return $this->belongsTo(Category::class, 'sub_category_id', 'id');
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function extraShipping(){
        return $this->hasMany(ExtraOption::class);
    }

    public function store(){
        return $this->belongsTo(Store::class);
    }

    
    public function listingReports(){
        return $this->hasMany(Report::class, 'reported_id')->where('is_store', 0);
    }

    public function coupon(){
        return $this->hasMany(Promocode::class);
    }

    public function favorited(){
        return $this->hasMany(FavoriteListing::class, 'product_id');
    }

    public function views(){
        return $this->hasMany(ProductView::class, 'product_id');
    }
}
