<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVideo extends Model
{
    use HasFactory;

    protected $fillable = [
      'product_id',
      'path',
    ];

    public function product(){
      return $this->belongs(Product::class);
    }

    public function getPathAttribute($value)
    {
      return $value && Storage::disk('upload')->exists($value)
      ? Storage::disk('upload')->url($value)
      : null;
    }
}
