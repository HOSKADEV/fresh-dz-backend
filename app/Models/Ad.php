<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ad extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    //'name',
    'name_ar',
    'name_en',
    'name_fr',
    'image',
    'type',
    'url',
  ];

  public function getImageAttribute($value)
  {
    return $value && Storage::disk('upload')->exists($value)
      ? Storage::disk('upload')->url($value)
      : null;
  }

  public function name($lang = null)
  {
    $lang = $lang ?? session('locale', app()->getLocale());

    return match ($lang) {
      'en' => $this->name_en ?? $this->name_ar,
      'fr' => $this->name_fr ?? $this->name_ar,
      'ar' => $this->name_ar,
      default => $this->name_ar
    };
  }

  public function getNameAttribute()
  {
    return $this->name();
  }

  public function product_ad()
  {
    return $this->hasOne(ProductAd::class);
  }

  public function product()
  {
    return $this->hasOneThrough(Product::class, ProductAd::class, 'ad_id', 'id', 'id', 'product_id');
  }

  public function section()
  {
    return Section::withTrashed()->where('type', 'ad')->where('element', $this->id)->first();
  }
}
