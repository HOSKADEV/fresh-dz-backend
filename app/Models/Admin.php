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

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
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

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
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
      default => 'Unknown',
    };
  }
}
