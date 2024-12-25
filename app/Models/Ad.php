<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ad extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'name',
      'image',
      'type',
      'url',
    ];

    public function getImageAttribute($value)
    {
      return Storage::disk('upload')->exists($value)
      ? Storage::disk('upload')->url($value)
      : null;
    }

    public function product_ad(){
      return $this->hasOne(ProductAd::class);
    }

    public function product(){
      return $this->hasOneThrough(Product::class, ProductAd::class, 'ad_id', 'id', 'id', 'product_id');
    }
}
