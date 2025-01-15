<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'user_id',
      'cart_id',
      'region_id',
      'phone',
      'status',
      'longitude',
      'latitude',
      'delivery_time',
      'note',
    ];

    protected $casts = [
      'user_id' => 'integer',
      'cart_id' => 'integer',
      'longitude' => 'double',
      'latitude' => 'double',
    ];

    protected $softCascade = ['invoice','delivery'];

    public function user(){
      return $this->belongsTo(User::class);
    }

    public function cart(){
      return $this->belongsTo(Cart::class);
    }

    public function region(){
      return $this->belongsTo(Region::class);
    }

    public function items(){
      return $this->cart->items;
    }

    public function invoice(){
      return $this->hasOne(Invoice::class);
    }

    public function delivery(){
      return $this->hasOne(Delivery::class);
    }

    public function review(){
      return $this->hasOne(Review::class);
    }

    public function phone(){
      /* return is_null($this->phone) ? null : '0'.$this->phone; */
      return $this->phone;
    }

    public function address(){
      return 'https://maps.google.com/?q='.$this->latitude.','.$this->longitude;
    }

    public function whatsapp(){
      return 'https://wa.me/'.$this->delivery?->driver?->phone().'?text=' .
      __('order N') . ': ' . $this->id . '%0A' .
      __('Total amount') . ': ' . number_format($this->invoice->total_amount, 2, '.', ',') . '%0A' .
      __('Phone') . ': ' . $this->phone() . '%0A' .
      __('Invoice') . ': ' . $this->invoice->file . '%0A' .
      __('Location') . ': ' . $this->address();
    }

    public function notify(){

      $admins = Admin::where('role',1)->orWhere('region_id',$this->region_id)->pluck('id')->toArray();

      $beamsClient = new \Pusher\PushNotifications\PushNotifications(Set::pusher_credentials());

      $publishResponse = $beamsClient->publishToUsers(
        $admins,
        [
          "web" => [
            "notification" => [
              "title" => trans('messages.order.created.title',['order_id' => $this->id]),
              "body" => trans('messages.order.created.content',['region_name' => $this->region?->name]),
              'deep_link' => url('order/browse'),
            ]
          ]
      ]);

    }

}
