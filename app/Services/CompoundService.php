<?php

namespace App\Services;

use App\Models\ItemCompound;

class CompoundService
{
    public function __construct(protected ItemCompound $model){}


    private function find($id) : object
    {
        $item = $this->model->query()->find($id);

        if (!$item)
        {
            throw new \Exception('Города с таким ID не существует');
        }

        return $item;
    }

    public function create($attributes,$id)
    {

        $data['item_id'] = $id;
        $data['name_ru'] = $attributes['name_ru'];
        $data['name_tr'] = $attributes['name_tr'];
        $data['name_kz'] = $attributes['name_kz'];
        $data['percent'] = $attributes['percent'];

        $item = $this->model->query()->create($data);

        return $item;
    }


    public function update($attributes, $id)
    {
        $item = $this->find($id);
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
