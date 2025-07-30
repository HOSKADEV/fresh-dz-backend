<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
  public function toArray($request)
  {
    $discount = $this->discount();
    $images = $this->images()->get('path')->pluck('path')->toArray();
    $videos = $this->videos()->get('path')->pluck('path')->toArray();
    $this->image ? array_unshift($images, $this->image) : $images;

    return [
      'id' => $this->id,
      'subcategory_id' => $this->subcategory_id,
      'category_id' => $this->subcategory->category_id,
      'name' => $this->name,
      'unit_name' => $this->name,
      'pack_name' => $this->name,
      'name_en' => $this->name_en,
      'name_fr' => $this->name_fr,
      'name_ar' => $this->name_ar,
      'unit_price' => $this->unit_price,
      'pack_price' => $this->pack_price,
      'pack_units' => $this->pack_units,
      'unit_id' => $this->unit_id,
      'unit_type' => $this->unit?->name,
      'status' => $this->status,
      'image' => $this->image,
      'is_discounted' => is_null($discount) ? false : true,
      'discount_amount' => is_null($discount) ? 0 : $discount->amount,
      'start_date' => is_null($discount) ? null : $discount->start_date,
      'end_date' => is_null($discount) ? null : $discount->end_date,
      'in_cart' => empty($this->in_cart()) ? false : true,
      'quantity' => $this->in_cart(),
      'description' => $this->description,
      'images' => $images,
      'videos' => $videos,
    ];
  }
}
