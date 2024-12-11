<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
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
          'product_id' => $this->product_id,
          //'type' => $this->type,
          'amount' => $this->amount,
          'start_date' => date('Y-m-d',strtotime($this->start_date)),
          'end_date' => date('Y-m-d',strtotime($this->end_date)),
        ];
    }
}
