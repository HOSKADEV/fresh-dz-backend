<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function update(Request $request){
      $validator = Validator::make($request->all(), [
        'invoice_id' => 'required|exists:invoices,id',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]);
      }

      try{

      $invoice = Invoice::find($request->invoice_id);

      if(!is_null($invoice->file)){
        File::delete(url($invoice->file));
      }

      $invoice->pdf();
      $invoice->refresh();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => url($invoice->file)
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }

    }
}
