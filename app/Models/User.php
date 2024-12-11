<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //'firstname',
        //'lastname',
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

    public function carts(){
      return $this->hasMany(Cart::class);
    }

    public function orders(){
      return $this->hasMany(Order::class);
    }

    public function notifications(){
      return $this->hasMany(Notification::class);
    }

    public function fullname(){
      //return $this->firstname . ' ' . $this->lastname;
      return $this->name;
    }

    public function phone(){
      /* return is_null($this->phone) ? null : '0'.$this->phone; */
      return $this->phone;
    }

    public function cart(){
      $cart = $this->carts()->where('type','current')->first();

      if(is_null($cart)){
        $cart = Cart::create(['user_id' => $this->id, 'type' => 'current']);
      }else{
        foreach($cart->items as $item){
          if(is_null($item->product)){
            $item->delete();
          }
        }
      }

      return $cart;
    }


}
