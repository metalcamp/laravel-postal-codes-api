<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'country'     => CountryResource::make($this->whenLoaded('country')),
            'province'    => ProvinceResource::make($this->whenLoaded('province')),
            'postal_code' => PostalCodeResourceCollection::make($this->whenLoaded('postalCodes')),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
