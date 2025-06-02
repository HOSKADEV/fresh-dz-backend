<?php

namespace App\Http\Resources;

use App\Http\Resources\ReviewResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
      'id' => $this->id,
      'identifier' => $this->identifier,
      'cart_id' => $this->cart_id,
      'region_id' => $this->region_id,
      'region_name' => $this->region->name,
      'phone' => $this->phone(),
      'delivery_time' => $this->delivery_time,
      'longitude' => $this->longitude,
      'latitude' => $this->latitude,
      'status' => $this->status,
      'created_at' => date_format($this->created_at, 'Y-m-d H:i:s'),
      'updated_at' => date_format($this->updated_at, 'Y-m-d H:i:s'),
      'invoice' => is_null($this->invoice) ? null : new InvoiceResource($this->invoice),
      'review' => is_null($this->review) ? null : new ReviewResource($this->review),
    ];
  }
}
