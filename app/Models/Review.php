<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
      'order_id',
      'content',
      'score',
    ];

    public function order(){
      return $this->belongsTo(Order::class);
    }
}
