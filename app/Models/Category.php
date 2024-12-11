<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Support\Carbon;

class Category extends Model
{
  use HasFactory,SoftDeletes,SoftCascadeTrait;

    protected $fillable = [
      'name',
      'image',
    ];

    protected $softCascade = ['subcategories','members','category_offers'];

    public function subcategories(){
      return $this->hasMany(Subcategory::class);
    }

    public function members(){
      return $this->hasMany(Member::class);
    }
    public function category_offers(){
      return $this->hasMany(CategoryOffer::class);
    }
    public function families(){
      return Family::whereIn('id',$this->members->pluck('family_id')->toArray());
    }

    public function discounts(){
      $products = Product::whereIn('subcategory_id',$this->subcategories()->pluck('id')->toArray())
      ->join('discounts','products.id','discounts.product_id')
      ->WhereRaw('? between start_date and end_date', Carbon::now()->toDateString())->where('discounts.deleted_at',null)
      ->select('products.*','discounts.id as discount_id','discounts.amount','discounts.start_date','discounts.end_date')
      ->inRandomOrder();

      return $products;
    }
}
