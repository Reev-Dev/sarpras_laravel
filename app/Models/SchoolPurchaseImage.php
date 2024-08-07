<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolPurchaseImage extends Model
{
    use HasFactory;
    protected $fillable = ['school_purchase_id', 'path'];

    public function schoolPurchase()
    {
        return $this->belongsTo(SchoolPurchase::class);
    }
}
