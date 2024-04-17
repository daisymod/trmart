<?php

namespace App\Services;

use App\Models\Brand;

class BrandService
{
    public function __construct(protected Brand $model){}


    private function find($id) : object
    {
        $item = $this->model->query()->find($id);

        if (!$item)
        {
            throw new \Exception('Бренда с таким ID не существует');
        }

        return $item;
    }

    public function create($attributes)
    {
        $attributes['image'] = '['.$attributes['image'] . ']';

        $item = $this->model->query()->create($attributes);
        return $item;
    }


    public function update($attributes, $id)
    {
        if (!empty($attributes['image'])){
            $attributes['image'] = '['.$attributes['image'] . ']';
        }
        $item = $this->find($id);
        return $item->update($attributes);
    }

    public function getAll()
    {
        $items =  $this->model->query()
            ->get();

        foreach ($items as $item){
            $images = json_decode($item->image);
            $item->main_img = $images[0]->img;
        }

        return $items;

    }


    public function getById($id)
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
        $item = $this->find($id);

        return $item->delete();
    }


}
