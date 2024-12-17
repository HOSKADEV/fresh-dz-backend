<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use LaravelDaily\Invoices\Invoice as Bill;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
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


      $seller = new Party([
          'name'          => __('Fresh'),
      ]);

      $buyer = new Party([
          'name' => $user->fullname(),
          'phone' => $order->phone(),
      ]);

      $items = [];

      foreach($cart->items as $item){
        array_push($items,
        (new InvoiceItem())
          ->title($item->name())
          //->description('Your product or service description')
          //->pricePerUnit($item->price()*(1-($item->discount/100)))
          ->pricePerUnit($item->price())
          ->quantity($item->quantity)
          ->discountByPercent($item->discount)
      );
      }



    $filename = $this->id.'-'. Carbon::now()->toDateString();

    $invoice = Bill::make(__('receipt'))
        ->series('ID')
        // ability to include translated invoice status
        // in case it was paid
        //->status(__('invoices::invoice.paid'))
        ->sequence($order->id)
        ->serialNumberFormat('{SERIES}/{SEQUENCE}')
        ->seller($seller)
        ->buyer($buyer)
        ->date($order->created_at)
        ->dateFormat('Y-m-d')
        ->totalTaxes($this->tax_amount)
        ->totalDiscount($this->discount_amount)
        //->payUntilDays(14)
        ->currencySymbol(__(''))
        ->currencyCode(__(''))
        ->currencyFormat('{SYMBOL}{VALUE}')
        ->currencyThousandsSeparator(',')
        ->currencyDecimalPoint('.')
        ->filename($filename)
        ->addItems($items)
        //->logo(public_path('logo.jpeg'))
        ;
        // You can additionally save generated invoice to configured disk

        if(!is_null($order->note)){
          $invoice->notes($order->note);
        }

        //dd($invoice->render());

        $invoice->save('invoice');

        //dd($invoice->toHtml());

        $this->file = 'uploads/invoices/'.$filename.'.pdf';

        $this->save();


        return;



    }

}
