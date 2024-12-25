<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Services\InvoiceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Invoice\UserResource;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Resources\Invoice\ItemCollection;
use App\Http\Resources\Invoice\InvoiceResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'checkout_id',
      'order_id',
      'purchase_amount',
      //'tax_type',
      'tax_amount',
      'discount_amount',
      'total_amount',
      'discount_code',
      'file',
      'is_paid',
      'paid_at',
      'payment_method',
      'payment_account',
      'payment_receipt',
    ];

    public function getFileAttribute($value)
    {
      return Storage::disk('upload')->exists($value??'')
      ? Storage::disk('upload')->url($value)
      : null;
    }

    public function order(){
      return $this->belongsTo(Order::class);
    }

    public function coupon(){
      return $this->belongsTo(Coupon::class, 'discount_code', 'code');
    }

    public function total(){

      $this->purchase_amount = $this->order->cart->items()->sum('amount');

      /* if($this->tax_type == 'fixed'){
        $this->total_amount = $this->purchase_amount + $this->tax_amount;
      }else{
        $this->total_amount = $this->purchase_amount * (1 + ($this->tax_amount/100));
      } */

      if($this->discount_code){
        $this->discount_amount = $this->purchase_amount * ($this->coupon->discount / 100);
      }

      $this->total_amount = $this->purchase_amount + $this->tax_amount - $this->discount_amount;

      $this->save();

      return $this->total_amount;
    }


    public function pdf(){
      $order = $this->order;
      $user = $order->user;
      $cart = $order->cart;

      $invoice = new InvoiceService(
        (new UserResource(new User(['name' => 'Fresh', 'phone' => '1234567890'])))->toArray(request()),
        (new UserResource($user))->toArray(request()),
        (new ItemCollection($cart->items))->toArray(request()),
        (new InvoiceResource($this))->toArray(request()),
        Carbon::now(),
        $order->note,
      );


        $this->file = $invoice->generatePdf();

        $this->save();

        return $this->file;

    }

}
