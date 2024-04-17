<?php

namespace App\Http\Resources;

//Illuminate
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
//App
use App\Models\CatalogItem;
use App\Models\User;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $catalog = CatalogItem::query()->where('id', $this->catalog_item_id);

        return [
            'catalog_item_id'   => $this->catalog_item_id,
            'salesman'          => User::query()->where('id', $catalog->value('user_id'))->value('shot_name'),
            'article'           => $this->article,
            'image'             => json_decode($this->image, true),
            'brand'             => $catalog->value('brand'),
            'count'             => $this->count,
            'price'             => $this->price,
            'total'             => $this->price * $this->count,
            'name'              => $this->name,
            'color'             => $this->color,
            'size'             => $this->size,
            'id'                => $this->id,
            'price_tenge'       => number_format($this->price_tenge, 2, '.', ' ') ?? 0,
            'sale'        => 	$this->sale,
        ];
    }
}
