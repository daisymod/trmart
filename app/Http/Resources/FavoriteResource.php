<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\CatalogItem;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $product = CatalogItemForFavoriteResource::collection(
            CatalogItem::query()->where('id', $this->catalog_items_id)->get()
        )->resolve();

        return [
            'id'            => $this->id,
            'user_id'       => $this->user_id,
            'catalog_id'    => $product[0]['catalog_id'],
            'name_ru'       => $product[0]['name_ru'],
            'name_kz'       => $product[0]['name_kz'],
            'name_tr'       => $product[0]['name_tr'],
            'price'         => $product[0]['price'],
            'image'         => $product[0]['image'],
            'brand'         => $product[0]['brand']
        ];
    }
}
