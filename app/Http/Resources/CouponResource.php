<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
          'name' => $this->name,
          'code' => $this->code,
          'discount' => $this->discount,
          'start_date' => $this->start_date ? date('Y-m-d', strtotime($this->start_date)) : null,
          'end_date' => $this->end_date ? date('Y-m-d',strtotime($this->end_date)) : null,
          'max_uses' => $this->max_uses,
        ];
    }
}
