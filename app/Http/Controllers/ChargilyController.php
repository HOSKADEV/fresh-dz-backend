<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Set;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Chargily\ChargilyPay\ChargilyPay;
use Chargily\ChargilyPay\Auth\Credentials;

class ChargilyController extends Controller
{
  public function callback(Request $request)
  {
    try {
      //dd($request->checkout_id);

      if (empty($request->checkout_id)) {
        throw new Exception('no checkout id');
      }

      $chargily_pay = new ChargilyPay(new Credentials(config('chargily.credentials')));
      $checkout = $chargily_pay->checkouts()->get($request->checkout_id);

      if (empty($checkout)) {
        throw new Exception('invalid checkout id');
      }

      $invoice = Invoice::where('checkout_id', $checkout->getId())->first();


      if (empty($invoice)) {
        throw new Exception('no invoice found');
      }

      if ($invoice->is_paid == 'yes') {
        throw new Exception('purchase already settled');
      }

      $order = $invoice->order;

      $user = $order->user;

      $cart = $order->cart;

      $diff_customer = $user->customer_id != $checkout->getCustomerId();

      $diff_cart = $cart->id != json_decode($checkout->getMetadata()[0])->cart_id;

      //dd($checkout);

      if ($diff_customer || $diff_cart) {
        throw new Exception('conflicted informations');
      }


      if ($checkout->getStatus() == 'success') {


        $invoice->is_paid = 'yes';
        $invoice->paid_at = now();
        $invoice->save();


        return redirect()->route('chargily-success');

      } else {

        $cart->type = 'current';
        $order->status = 'canceled';
        $cart->save();
        $order->save();

        return redirect()->route('chargily-failed');

      }


    } catch (Exception $e) {
      dd($e->getMessage());
      return redirect()->route('error');
    }
  }

  public function success()
  {
    return view('chargily.success');
  }

  public function failed()
  {
    $number = Set::where('name', 'whatsapp')->value('value');
    return view('chargily.failed')->with('number', $number);
  }
}
