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
          'subcategory_id' => is_null($product) ? null : $product->subcategory_id,
          'category_id' => is_null($subcategory) ? null : $subcategory->category_id,
          'unit_name' => empty($this->unit_name) ? $product->unit_name : $this->unit_name ,
          'pack_name' => empty($this->pack_name) ? $product->pack_name : $this->pack_name ,
          'unit_price' => empty($this->unit_price) ? $product->unit_price : $this->unit_price ,
          'pack_price' => empty($this->pack_price) ? $product->pack_price : $this->pack_price ,
          'pack_units' => empty($this->pack_units) ? $product->pack_units : $this->pack_units ,
          'unit_type' => is_null($product) ? null : $product->unit_type,
          'discount_amount' => $discount ,
          'status' => is_null($product) ? null : $product->status,
          'image' => is_null($product) ? null : (empty($product->image) ? null : url($product->image)),
          'quantity' => $this->quantity
        ];
    }
}
