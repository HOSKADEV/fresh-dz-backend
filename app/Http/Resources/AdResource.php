<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
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
          'name' => $this->name,
          'name_ar' => $this->name_ar,
          'name_en' => $this->name_en,
          'name_fr' => $this->name_fr,
          'type' => $this->type,
          'url' => $this->url,
          'image' => $this->image,
          'product_id' => $this->product?->id
        ];
    }
}
