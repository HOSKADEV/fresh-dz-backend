<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name_ar',
    'name_en'
  ];


  public function products(){
    return $this->hasMany(Product::class);
  }

  public function name($lang = 'ar')
  {
    return match ($lang) {
      'ar' => $this->name_ar,
      'en' => $this->name_en,
      default => $this->name_en,
    };
  }
}
