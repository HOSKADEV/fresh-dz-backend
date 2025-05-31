<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

  public function getNameAttribute()
  {
    return $this->firstname . ' ' . $this->lastname;
  }

  public function getImageAttribute($value)
  {
    return $value && Storage::disk('upload')->exists($value)
      ? Storage::disk('upload')->url($value)
      : null;
  }

  public function deliveries()
  {
    return $this->hasMany(Delivery::class);
  }

  public function fullname()
  {
    return $this->firstname . ' ' . $this->lastname;
  }


  public function status()
  {
    $deliveries = $this->deliveries()->where('delivered_at', null)->count();

    if ($deliveries == 0) {
      return true;
    }

    return false;
  }

  public function phone()
  {
    if (!$this->phone) {
      return null;
    }

    return preg_replace('/^0/', '+213', $this->phone);
  }


}
