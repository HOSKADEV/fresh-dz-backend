<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        'order_id' => $this->order_id,
        'purchase_amount' => $this->purchase_amount,
        'tax_amount' => $this->tax_amount,
        'discount_amount' => $this->discount_amount,
        'total_amount' => $this->total_amount,
      ];
    }
}
