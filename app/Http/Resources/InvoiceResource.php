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
          'total_amount' => $this->total_amount,
          'file' => is_null($this->file) ? null : url($this->file),
          'is_paid' => $this->is_paid,
          'paid_at' => $this->paid_at,
          'payment_method' => $this->payment_method,
        ];
    }
}
