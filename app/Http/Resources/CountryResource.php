<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'cities'     => CityResourceCollection::make($this->whenLoaded('cities')),
            'provinces'  => ProvinceResourceCollection::make($this->whenLoaded('provinces')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
