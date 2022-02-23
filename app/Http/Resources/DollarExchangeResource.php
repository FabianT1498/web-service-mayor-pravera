<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DollarExchangeResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (is_null($this->resource)){
            return ['data' => null];
        }

        return [
            'bs_exchange' => $this->bs_exchange,
            'created_at' => $this->created_at,
        ];
    }
}
