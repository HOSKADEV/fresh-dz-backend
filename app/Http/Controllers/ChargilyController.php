<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Set;
use App\Models\Notice;
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

      $chargily_pay = new ChargilyPay(new Credentials(Set::chargily_credentials()));
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

      if ($user->customer_id != $checkout->getCustomerId()) {
        throw new Exception('conflicted informations');
      }


      if ($checkout->getStatus() == 'paid') {


        $invoice->is_paid = 'yes';
        $invoice->paid_at = now();
        $cart->type = 'order';
        $order->status = 'accepted';
        $invoice->save();
        $cart->save();
        $order->save();

        $user->notify(Notice::OrderNotice($order->id,'accepted'));

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
