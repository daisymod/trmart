<?php

namespace App\Services;

use App\Models\CatalogItem;
use App\Models\Feedback;
use App\Models\ItemCompound;
use Illuminate\Support\Facades\Auth;

class ItemService
{
    public function __construct(protected CatalogItem $model){}




    public function create($attributes,$user)
    {
        $getLastId = CatalogItem::orderbyDesc('id')->first();

        $data['name_ru'] = $attributes['name']['ru'];
        $data['name_tr'] = $attributes['name']['tr'];
        $data['name_kz'] = $attributes['name']['kz'];
        $data['brand'] = $attributes['brand'];
        $data['article'] = empty($attributes['article']) ? substr("0000000000".$getLastId->id + 1, strlen($getLastId->id + 1)) : $attributes['article'];
        $data['barcode'] = $attributes['barcode'];
        $data['country_title'] = $attributes['country_title'] ?? null;
        $data['city_id'] = $attributes['city_id'] ?? null;
        $data['country_id'] = $attributes['country_id'] ?? null;
        $data['equipment_ru'] = $attributes['equipment']['ru'];
        $data['equipment_tr'] = $attributes['equipment']['tr'];
        $data['equipment_kz'] = $attributes['equipment']['kz'];
        $data['body_ru'] = $attributes['body']['ru'];
        $data['body_tr'] = $attributes['body']['tr'];
        $data['body_kz'] = $attributes['body']['kz'];
        $data['active'] = $user->role == 'merchant' ? 'N' : $attributes['active'] ?? 'Y';
        $data['status'] = $user->role == 'merchant' ? 1 : intval($attributes['status']);
        $data['status_text'] = "";
        $data['stock'] = $attributes['stock'] ?? 1;
        $data['sex'] = $attributes['sex'] ?? null;
        $data['weight'] = $attributes['weight'] ?? 1;
        $data['style'] = $attributes['style']?? null;
        $data['size'] = $attributes['size'] ?? null;
        $data['sale'] = $attributes['sale'] ?? 0;
        $data['length'] = $attributes['length'] ?? null;
        $data['price'] = $attributes['price'] ?? 0 ;
        $data['count'] = $attributes['count'] ?? 1;
        $data['catalog_id'] = $attributes['catalog'][0] ?? 1;
        $data['user_id'] = $attributes['user'][0] ?? $user->id;
        $data['image'] =  gettype($attributes['image']) == 'string' ?  '['.$attributes['image'] .']' : '['.implode(",",$attributes['image']).']';
        $data['reason'] = $attributes['reason'] ?? null;

        $item = $this->model->query()->create($data);

        return $item;
    }


    public function checkExist($attributes){
        $item = $this->model->query()
            ->where('name_ru','=',$attributes['name']['ru'])
            ->first();

        return $item;
    }

    public function update($attributes,$id,$user){
        $data['name_ru'] = $attributes['name']['ru'];
        $data['name_tr'] = $attributes['name']['tr'];
        $data['name_kz'] = $attributes['name']['kz'];
        $data['brand'] = $attributes['brand'];
        $data['barcode'] = $attributes['barcode'];
        $data['country_title'] = $attributes['country_title'] ?? null;
        $data['city_id'] = $attributes['city_id'] ?? null;
        $data['country_id'] = $attributes['country_id'] ?? null;
        $data['equipment_ru'] = $attributes['equipment']['ru'];
        $data['equipment_tr'] = $attributes['equipment']['tr'];
        $data['equipment_kz'] = $attributes['equipment']['kz'];
        $data['body_ru'] = $attributes['body']['ru'];
        $data['body_tr'] = $attributes['body']['tr'];
        $data['body_kz'] = $attributes['body']['kz'];
        $data['active'] = $attributes['active'];

        $data['status_text'] =  '';
        $data['sex'] = $attributes['sex'] ?? null;
        $data['stock'] = $attributes['stock'] ?? 1;
        $data['weight'] = $attributes['weight'] ?? 1;
        $data['style'] = $attributes['style'] ?? null;
        $data['size'] = $attributes['size'] ?? null;
        $data['sale'] = $attributes['sale'] ?? 0;
        $data['length'] = $attributes['length'] ?? null;
        $data['price'] = $attributes['price'] ?? 0 ;
        $data['count'] = $attributes['count'] ?? 1;
        $data['catalog_id'] = $attributes['catalog'][0] ?? 1;
        $data['user_id'] = $attributes['user'][0] ?? $user->id;
        $data['reason'] = $attributes['reason'] ?? null;
        $data['image'] =  gettype($attributes['image']) == 'string' ?  '['.$attributes['image'] .']' : '['.implode(",",$attributes['image']).']';
        $item = $this->model->query()->find($id);



        if ($user->role == 'admin'){
            $data['status'] = intval($attributes['status']);
        }elseif ($user->role == 'merchant' &&
            $item->name_ru == $data['name_ru'] &&
            $item->name_kz== $data['name_kz'] &&
            $item->name_tr == $data['name_tr'] &&

            $item->body_ru == $data['body_ru'] &&
            $item->body_tr == $data['body_tr'] &&
            $item->body_kz == $data['body_kz'] &&
            $item->status == 2){

            $data['status'] = $item->status;
        }else{
            $data['status'] = $user->role == 'merchant' ? 1 : intval($attributes['status']);
        }

        return $item->update($data);

    }

}
