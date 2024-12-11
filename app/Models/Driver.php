<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'firstname',
      'lastname',
      'image',
      'phone',
      'status',
    ];

    public function deliveries(){
      return $this->hasMany(Delivery::class);
    }

    public function fullname(){
      return $this->firstname . ' ' . $this->lastname;
    }


    public function status(){
      $deliveries = $this->deliveries()->where('delivered_at',null)->count();

      if($deliveries == 0){
        return true;
      }

      return false;
    }

    public function phone(){
      /* return is_null($this->phone) ? null : '0'.$this->phone; */
      return $this->phone;
    }


}
