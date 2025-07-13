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
  use HasFactory, SoftDeletes, SoftCascadeTrait;

  protected $fillable = [
    'subcategory_id',
    'unit_id',
    'name_en',
    'name_fr',
    'name_ar',
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
    return $value && Storage::disk('upload')->exists($value)
      ? Storage::disk('upload')->url($value)
      : null;
  }

  public function subcategory()
  {
    return $this->belongsTo(Subcategory::class);
  }

  public function unit()
  {
    return $this->belongsTo(Unit::class);
  }

  public function items()
  {
    return $this->hasMany(Item::class);
  }

  public function images()
  {
    return $this->hasMany(ProductImage::class);
  }

  public function videos()
  {
    return $this->hasMany(ProductVideo::class);
  }

  public function category()
  {
    return $this->subcategory->category;
  }

  public function discounts()
  {
    return $this->hasMany(Discount::class);
  }

  public function ads()
  {
    return $this->hasManyThrough(Ad::class, ProductAd::class);
  }

  public function reminders()
  {
    return $this->hasMany(Reminder::class);
  }

  public function users_to_remind()
  {
    return $this->hasManyThrough(User::class, Reminder::class, 'product_id', 'id', 'id', 'user_id');
  }

  public function active_discount()
  {
    return $this->discounts()->where([
      ['start_date', '<=', now()],
      ['end_date', '>=', now()]
    ]);
  }

  public function discount()
  {
    /* return Discount::where('product_id',$this->id)
    ->WhereRaw('? between start_date and end_date', Carbon::now()->toDateString())
    ->first(); */
    return $this->active_discount()->first();
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

  public function has_pack()
  {
    if (empty($this->pack_price) || empty($this->pack_units)) {
      return false;
    }
    return true;
  }

  public function in_cart()
  {

    $user = auth()->user();

    $item = Item::whereHas('cart', function ($query) use ($user) {
      $query->where('user_id', $user?->id)->where('type', 'current');
    })->where('product_id', $this->id)->first();


    return $item?->quantity ?? 0;

  }


  public function add_to_cart($cart_id, $quantity, $discount)
  {

    if ($this->has_pack() && $quantity >= $this->pack_units) {
      $pack_quantity = intdiv($quantity, $this->pack_units);
      $amount = $pack_quantity * ($this->pack_price * (1 - ($discount / 100)));

      Item::create([
        'cart_id' => $cart_id,
        'product_id' => $this->id,
        'name_ar' => $this->name_ar,
        'name_en' => $this->name_en,
        'name_fr' => $this->name_fr,
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
        'name_ar' => $this->name_ar,
        'name_en' => $this->name_en,
        'name_fr' => $this->name_fr,
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

  public function notify($status)
  {
    if ($status == 'available' && $this->reminders()->count()) {
      $notice = Notice::ProductNotice($this->id, $this->name, $this->image, $status);
      $users = $this->users_to_remind();
      Notification::send($notice, $users);
      $this->reminders()->delete();
    }
  }

}
