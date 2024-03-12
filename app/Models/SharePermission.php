<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePermission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function shareAccess(){
        return $this->hasOne(ShareAccess::class, 'share_access_id');
    }
}
