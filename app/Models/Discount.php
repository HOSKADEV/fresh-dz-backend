<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'product_id',
      //'name',
      //'type',
      'amount',
      'start_date',
      'end_date',
    ];

    protected $casts = [
      'product_id' => 'integer',
      'amount' => 'double',
    ];

    public function product(){
      return $this->belongsTo(Product::class);
    }


}
