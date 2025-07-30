<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      $product = $this->product()->withTrashed()->first();
      $subcategory = is_null($product) ? null : $product->subcategory()->withTrashed()->first();

      if($this->cart->type == 'current'){
        $discount = is_null($product) ? 0 : (is_null($product->discount()) ? 0 : $product->discount()->amount);
      }else{
        $discount = $this->discount;
      }
        return [
          'product_id' => $this->product_id,
          'subcategory_id' => $product?->subcategory_id,
          'category_id' => $subcategory?->category_id,
          'name' => $this->name ?? $product?->name,
          'unit_name' => $this->name ?? $product?->name,
          'pack_name' => $this->name ?? $product?->name,
          'unit_price' => $this->unit_price(),
          'pack_price' => $this->pack_price(),
          'pack_units' => $this->pack_units(),
          'unit_id' => $product?->unit_id,
          'unit_type' => $product?->unit?->name,
          'discount_amount' => $discount ,
          'status' => $product?->status,
          'image' => $product?->image,
          'quantity' => $this->quantity
        ];
    }
}
