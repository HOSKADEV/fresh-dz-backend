<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
  use HasFactory, SoftDeletes, SoftCascadeTrait;
  protected $fillable = [
    'name',
    'longitude',
    'latitude',
    'boundaries'
  ];

  protected $softCascade = ['admins'];

  public function admins(){
    return $this->hasMany(Admin::class);
  }

  public function orders(){
    return $this->hasMany(Order::class);
  }
}
