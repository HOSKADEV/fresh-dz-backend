<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'code',
      'name',
      'discount',
      'start_date',
      'end_date',
      'max_uses'
  ];

  public function uses(){
    return Invoice::where('code', $this->code)->count();
  }

}
