<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DormPurchaseImage extends Model
{
    use HasFactory;
    protected $fillable = ['dorm_purchase_id', 'path'];
    public function dormPurchase()
    {
        return $this->belongsTo(DormPurchase::class);
    }
}
