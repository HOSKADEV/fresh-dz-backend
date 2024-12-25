<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
          "customer_id" => $this->customer_id,
          'name' => $this->name,
          'email' => $this->email,
          'phone' => $this->phone(),
          'image' => $this->image,
        ];
    }
}
