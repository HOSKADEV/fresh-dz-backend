<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDiscountResource extends JsonResource
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
          'product_id' => $this->id,
          'subcategory_id' => $this->subcategory_id,
          'category_id' => $this->subcategory->category_id,
          'unit_name' => $this->unit_name,
          'pack_name' => $this->pack_name,
          'unit_price' => $this->unit_price,
          'pack_price' => $this->pack_price,
          'pack_units' => $this->pack_units,
          'unit_type' => $this->unit_type,
          'status' => $this->status,
          'image' => empty($this->image) ? null : url($this->image),
          'is_discounted' => is_null($this->discount_id) ? false : true,
          'discount_amount' => is_null($this->amount) ? 0 : $this->amount,
          'start_date' => $this->start_date,
          'end_date' => $this->end_date,
          'in_cart' => empty($this->in_cart()) ? false : true,
          'quantity' => $this->in_cart()
        ];
    }
}
