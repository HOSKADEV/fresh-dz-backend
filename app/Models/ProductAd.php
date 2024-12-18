<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAd extends Model
{
    use HasFactory;

    protected $fillable = [
      'product_id',
      'ad_id',
    ];

    public function product(){
      return $this->belongsTo(Product::class);
    }

    public function ad(){
      return $this->belongsTo(Ad::class);
    }
}
