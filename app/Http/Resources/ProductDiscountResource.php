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

      $images = $this->images()->get('path')->pluck('path')->toArray();
      $videos = $this->videos()->get('path')->pluck('path')->toArray();

      $this->image ? array_unshift($images,$this->image) : $images;

        return [
          'product_id' => $this->id,
          'subcategory_id' => $this->subcategory_id,
          'category_id' => $this->subcategory->category_id,
          'name' => $this->name,
          'unit_name' => $this->name,
          'pack_name' => $this->name,
          'unit_price' => $this->unit_price,
          'pack_price' => $this->pack_price,
          'pack_units' => $this->pack_units,
          'unit_id' => $this->unit_id,
          'unit_type' => $this->unit?->name,
          'status' => $this->status,
          'image' => $this->image,
          'is_discounted' => is_null($this->discount_id) ? false : true,
          'discount_amount' => is_null($this->amount) ? 0 : $this->amount,
          'start_date' => $this->start_date,
          'end_date' => $this->end_date,
          'in_cart' => empty($this->in_cart()) ? false : true,
          'quantity' => $this->in_cart(),
          'description' => $this->description,
          'images' => $images,
          'videos' => $videos,
        ];
    }
}
