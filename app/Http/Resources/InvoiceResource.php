<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
          'purchase_amount' => $this->purchase_amount,
          'tax_amount' => $this->tax_amount,
          'discount_amount' => $this->discount_amount,
          'total_amount' => $this->total_amount,
          'discount_code' => $this->discount_code,
          'file' => $this->file_url,
          'is_paid' => $this->is_paid,
          'paid_at' => $this->paid_at,
          'payment_method' => $this->payment_method,
          'payment_account' => $this->payment_account,
          'payment_receipt' => $this->payment_receipt,
        ];
    }
}
