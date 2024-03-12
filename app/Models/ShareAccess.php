<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareAccess extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sharePermission(){
        return $this->hasMany(SharePermission::class, 'share_access_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function store(){
        return $this->belongsTo(Store::class);
    }
}
