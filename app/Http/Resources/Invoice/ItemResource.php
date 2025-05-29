<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'name' => $this->name(),
      'price' => $this->price(),
      'discount' => $this->price() * ($this->discount / 100),
      'quantity' => $this->quantity,
      'unit' => $this->product?->unit?->name_en,
      'subtotal' => $this->amount,
    ];
  }
}
