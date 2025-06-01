<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
  use HasFactory, /*Notifiable,*/ SoftDeletes;

  protected $fillable = [
    'region_id',
    'name',
    'email',
    'phone',
    'image',
    'password',
    'role',
    'status',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'id' => 'string',
    'email_verified_at' => 'datetime',
  ];

  public function getImageAttribute($value)
  {
    return $value && Storage::disk('upload')->exists($value)
      ? Storage::disk('upload')->url($value)
      : asset("assets/img/avatars/{$this->role}.png");
  }

  public function region()
  {
    $this->belongsTo(Region::class);
  }

  public function role()
  {
    return match ($this->role) {
      0 => 'Super Admin',
      1 => 'Admin',
      2 => 'Data Entry',
      3 => 'Region Manager',
      4 => 'Accountant',
      5 => 'Marketer',
      6 => 'Driver', // Add driver role
      default => 'Unknown',
    };
  }

  // Add driver-specific methods
  public function deliveries()
  {
    return $this->hasMany(Delivery::class, 'driver_id');
  }

  public function isDriver()
  {
    return $this->role === 6;
  }

  public function phone()
  {
    if (!$this->phone) {
      return null;
    }
    return preg_replace('/^0/', '+213', $this->phone);
  }
}
