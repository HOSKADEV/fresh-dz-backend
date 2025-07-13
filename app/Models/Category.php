<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
  use HasFactory, SoftDeletes, SoftCascadeTrait;

  protected $fillable = [
    'name_ar',
    'name_en',
    'name_fr',
    'image',
  ];

  protected $softCascade = ['subcategories', 'members', 'category_offers'];

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

  public function subcategories()
  {
    return $this->hasMany(Subcategory::class);
  }

  public function members()
  {
    return $this->hasMany(Member::class);
  }
  public function category_offers()
  {
    return $this->hasMany(CategoryOffer::class);
  }
  public function families()
  {
    return Family::whereIn('id', $this->members->pluck('family_id')->toArray());
  }

  public function discounts()
  {
    $products = Product::whereIn('subcategory_id', $this->subcategories()->pluck('id')->toArray())
      ->join('discounts', 'products.id', 'discounts.product_id')
      ->WhereRaw('? between start_date and end_date', Carbon::now()->toDateString())->where('discounts.deleted_at', null)
      ->select('products.*', 'discounts.id as discount_id', 'discounts.amount', 'discounts.start_date', 'discounts.end_date')
      ->inRandomOrder();

    return $products;
  }

  public function products()
  {
    return $this->hasManyThrough(Product::class, Subcategory::class, 'category_id', 'subcategory_id');
  }

  public function discounted_products()
  {
    return $this->products()
      ->whereNotNull('image')
      ->whereHas('active_discount');
  }
}
