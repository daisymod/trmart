<?php

namespace App\Services;

use App\Models\CatalogItemDynamicCharacteristic;

class CharacteristicService
{
    public function __construct(protected CatalogItemDynamicCharacteristic $model){}


    private function find($id) : object
    {
        $item = $this->model->query()->find($id);

        if (!$item)
        {
            throw new \Exception('Запись с таким ID не существует');
        }

        return $item;
    }

    public function create($attributes)
    {
        $item = $this->model->query()->create($attributes);

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
