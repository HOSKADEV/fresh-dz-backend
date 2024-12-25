<?php

namespace App\Models;

use Session;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
  use HasFactory,SoftDeletes,SoftCascadeTrait;

    protected $fillable = [
      'subcategory_id',
      'unit_name',
      'pack_name',
      'image',
      'unit_price',
      'pack_price',
      'pack_units',
      'unit_type',
      'status',
      'description'
    ];

    protected $casts = [
      'subcategory_id' => 'integer',
      'unit_price' => 'double',
      'pack_price' => 'double',
      'pack_units' => 'integer',
      'unit_type' => 'integer',
    ];

    protected $softCascade = ['discounts'];

    public function getImageAttribute($value)
    {
      return Storage::disk('upload')->exists($value ??'')
      ? Storage::disk('upload')->url($value)
      : null;
    }

    public function subcategory(){
      return $this->belongsTo(Subcategory::class);
    }

    public function items(){
      return $this->hasMany(Item::class);
    }

    public function images(){
      return $this->hasMany(ProductImage::class);
    }

    public function videos(){
      return $this->hasMany(ProductVideo::class);
    }

    public function category(){
      return $this->subcategory->category;
    }

    public function discounts(){
      return $this->hasMany(Discount::class);
    }

    public function ads(){
      return $this->hasManyThrough(Ad::class, ProductAd::class);
    }

    public function discount(){
      /* return Discount::where('product_id',$this->id)
      ->WhereRaw('? between start_date and end_date', Carbon::now()->toDateString())
      ->first(); */
      return $this->discounts()->where([
        ['start_date', '<=', now()],
        ['end_date', '>=', now()]
    ])->first();
    }

    public function has_pack(){
      if(empty($this->pack_name) || empty($this->pack_price) || empty($this->pack_units)){
        return false;
      }

      return true;
    }

    public function in_cart(){

      $user = auth()->user();

      $item = Item::whereHas('cart',function($query) use ($user){
        $query->where('user_id',$user?->id)->where('type','current');
      })->where('product_id',$this->id)->first();


      return $item?->quantity ?? 0;

    }


    public function add_to_cart($cart_id, $quantity, $discount){

      if ($this->has_pack() && $quantity >= $this->pack_units) {
        $pack_quantity = intdiv($quantity, $this->pack_units);
        $amount = $pack_quantity * ($this->pack_price * (1 - ($discount / 100)));

        Item::create([
          'cart_id' => $cart_id,
          'product_id' => $this->id,
          'unit_name' => $this->unit_name,
          'pack_name' => $this->pack_name,
          'unit_price' => $this->unit_price,
          'pack_price' => $this->pack_price,
          'pack_units' => $this->pack_units,
          'type' => 'pack',
          'quantity' => $pack_quantity,
          'discount' => $discount,
          'amount' => $amount

        ]);

        $quantity = $quantity % $this->pack_units;

      }
      if ($quantity > 0) {

        $amount = $quantity * ($this->unit_price * (1 - ($discount / 100)));
        Item::create([
          'cart_id' => $cart_id,
          'product_id' => $this->id,
          'unit_name' => $this->unit_name,
          'pack_name' => $this->pack_name,
          'unit_price' => $this->unit_price,
          'pack_price' => $this->pack_price,
          'pack_units' => $this->pack_units,
          'type' => 'unit',
          'quantity' => $quantity,
          'discount' => $discount,
          'amount' => $amount
        ]);
      }
    }

}
