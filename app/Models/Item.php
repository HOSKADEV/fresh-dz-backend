<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'cart_id',
      'product_id',
      'unit_name',
      'pack_name',
      'name_ar',
      'name_en',
      'name_fr',
      'unit_price',
      'pack_price',
      'pack_units',
      'type',
      'quantity',
      'discount',
      'amount'
    ];

    protected $casts = [
      'product_id' => 'integer',
      'cart_id' => 'integer',
      'unit_price' => 'double',
      'pack_price' => 'double',
      'pack_units' => 'integer',
      'quantity' => 'integer',
      'discount' => 'double',
      'amount' => 'double',
    ];

    public function cart(){
      return $this->belongsTo(Cart::class);
    }

    public function product(){
      return $this->belongsTo(Product::class);
    }

  public function name($lang = null)
  {
    $lang = $lang ?? session('locale', app()->getLocale());

        return match($lang) {
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

    public function unit_price(){
      if($this->cart->type == 'order'){
        return $this->unit_price;
      }else{
        return $this->product?->unit_price;
      }
    }

    public function pack_price(){
      if($this->cart->type == 'order'){
        return $this->pack_price;
      }else{
        return $this->product?->pack_price;
      }
    }

    public function pack_units(){
      if($this->cart->type = 'order'){
        return $this->pack_units;
      }else{
        return $this->product?->pack_units;
      }
    }

    public function price(){
      return $this->type == 'unit' ? $this->unit_price() : $this->pack_price();
    }

    public function amount(){

      $product = $this->product;
      $discount = is_null($product->discount()) ? 0 : $product->discount()->amount;
      $quantity = $this->quantity;
      $amount = 0;

      if ($product->has_pack() && $quantity >= $product->pack_units) {
        $pack_quantity = intdiv($quantity, $product->pack_units);
        $amount += $pack_quantity * ($product->pack_price * (1 - ($discount / 100)));
        $quantity = $quantity % $product->pack_units;

      }
      if ($quantity > 0) {
        $amount += $quantity * ($product->unit_price * (1 - ($discount / 100)));
      }

      return $amount;
    }

}
