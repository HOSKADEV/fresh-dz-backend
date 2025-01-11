<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, /*Notifiable,*/ SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'firstname',
    'lastname',
    'name',
    'email',
    'phone',
    'image',
    'password',
    'role',
    'status',
    'fcm_token'
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
      : $value;
  }

  public function carts()
  {
    return $this->hasMany(Cart::class);
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }

  public function notifications()
  {
    return $this->hasMany(Notification::class);
  }

  public function reminders(){
    return $this->hasMany(Reminder::class);
  }

  public function locations(){
    return $this->hasMany(Location::class);
  }

  public function fullname()
  {
    //return $this->firstname . ' ' . $this->lastname;
    return $this->name;
  }

  public function phone()
  {
    /* return is_null($this->phone) ? null : '0'.$this->phone; */
    return $this->phone;
  }

  public function cart()
  {
    $cart = $this->carts()->where('type', 'current')->first();

    if (is_null($cart)) {
      $cart = Cart::create(['user_id' => $this->id, 'type' => 'current']);
    } else {
      foreach ($cart->items as $item) {
        if (is_null($item->product)) {
          $item->delete();
        }
      }
    }

    return $cart;
  }

  public function update_status($status)
  {
    /* if(!$status){
      $this->tokens()->delete();
    } */

    $this->status = $status;
    $this->save();
  }

  public function notify(Notice $notice, $with_fcm = true)
  {
    Notification::create([
      'user_id' => $this->id,
      'notice_id' => $notice->id
    ]);

    if ($with_fcm && $this->fcm_token) {
      $controller = new \App\Http\Controllers\Controller();
      $controller->send_fcm_device(
        $notice->title(),
        $notice->content(),
        $this->fcm_token
      );
    }

  }


}
