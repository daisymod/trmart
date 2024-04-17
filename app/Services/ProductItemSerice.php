<?php

namespace App\Services;

use App\Models\ItemCompound;
use App\Models\ProductItem;

class ProductItemSerice
{
    public function __construct(protected ProductItem $model){}


    private function find($id) : object
    {
        $item = $this->model->query()->find($id);

        if (!$item)
        {
            throw new \Exception('Запись с таким ID не существует');
        }

        return $item;
    }

    public function create($attributes,$id)
    {
        if (isset($attributes['image'])) {
            $data['image'] =  gettype($attributes['image']) == 'string' ?  '['.$attributes['image'] .']' : '['.implode(",",$attributes['image']).']';
        }else{
            $data['image'] = null;
        }

        $data['item_id'] = $id;
        $data['color'] = $attributes['color'];
        $data['size'] = $attributes['size'];
        $data['count'] = empty($attributes['count']) ? 0 : $attributes['count'];
        $data['price'] = empty($attributes['price']) ? 0 : $attributes['price'];
        $data['sale'] = empty($attributes['sale']) ? 0 : $attributes['sale'];
        $item = $this->model->query()->create($data);

        return $item;
    }


    public function update($attributes, $id)
    {
        $item = $this->find($id);

        $attributes['count'] = empty($attributes['count']) ? 0 : $attributes['count'];
        $attributes['price'] = empty($attributes['price']) ? 0 : $attributes['price'];
        $attributes['sale'] = empty($attributes['sale']) ? 0 : $attributes['sale'];
        $data['image'] =  gettype($attributes['image']) == 'string' ?  '['.$attributes['image'] .']' : '['.implode(",",$attributes['image']).']';
        return $item->update($attributes);
    }

    public function getAll()
    {
        return $this->model->query()
            ->get();
    }

    public function show($id)
    {
        return $this->find($id);
    }

    public function delete($id)
    {
        $item = $this->model::query()->where('item_id','=',$id)
            ->delete();

        return $item;
    }


}
