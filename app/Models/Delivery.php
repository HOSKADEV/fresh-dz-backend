<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'driver_id',
      'order_id',
      'delivered_at'
    ];

    public function driver(){
      return $this->belongsTo(Admin::class, 'driver_id');
    }

    public function order(){
      return $this->belongsTo(Order::class);
    }
}
