<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public function reply(){
        return $this->hasOne(Reply::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
    
    public function store(){
        return $this->belongsTo(Store::class);
    }
    
    public static function claculateStoreRating($store_id){
        $storeReviews = Review::where('store_id', $store_id)->get();
        
        // Count the occurrences of each feedback type
        $positiveCount = $storeReviews->where('feedback', 'positive')->count();
        $neutralCount = $storeReviews->where('feedback', 'neutral')->count();
        $negativeCount = $storeReviews->where('feedback', 'negative')->count();
    
        // Assign weights to each feedback type
        $positiveWeight = 5.00;
        $neutralWeight = 3.35;
        $negativeWeight = 1.75;
    
        // Calculate the weighted sum
        $weightedSum = ($positiveCount * $positiveWeight) + ($neutralCount * $neutralWeight) + ($negativeCount * $negativeWeight);
    
        // Calculate the total count
        $totalCount = $positiveCount + $neutralCount + $negativeCount;
    
        // Calculate the weighted average
        if ($totalCount > 0) {
            $weightedAverage = $weightedSum / $totalCount;
        } else {
            $weightedAverage = 0.00; // to avoid division by zero
        }
    
        // You can now use $weightedAverage as needed
        return round($weightedAverage, 1);
    }
}
