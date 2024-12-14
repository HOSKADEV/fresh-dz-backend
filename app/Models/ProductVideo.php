<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
