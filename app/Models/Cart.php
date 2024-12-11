<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    protected $fillable = [
      'user_id',
      'type',
    ];

    protected $softCascade = ['items'];

    public function user(){
      return $this->belongsTo(User::class);
    }

    public function items(){
      return $this->hasMany(Item::class);
    }

    public function order(){
      return $this->hasOne(Order::class);
    }

    public function total(){
      if($this->type == 'order'){
        return $this->items()->sum('amount');
      }

      $total = 0;

      foreach($this->items as $item){
        $total += $item->amount();
      }

      return $total;

    }

}
