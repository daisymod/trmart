<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogItemForFavoriteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'catalog_id'    => $this->catalog_id,
            'name_ru'       => $this->name_ru,
            'name_kz'       => $this->name_kz,
            'name_tr'       => $this->name_tr,
            'brand'         => $this->brand,
            'price'         => $this->price,
            'image'         => $this->images()
        ];
    }
}
