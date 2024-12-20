<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'name',
      'image',
      'type',
      'url',
    ];

    public function product_ad(){
      return $this->hasOne(ProductAd::class);
    }

    public function product(){
      return $this->hasOneThrough(Product::class, ProductAd::class, 'ad_id', 'id', 'id', 'product_id');
    }
}
