<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\MarketplaceBrands;

class MarketPlaceBrandService
{
    public function __construct(protected MarketplaceBrands $model){}


    private function find($id) : object
    {
        $item = $this->model->query()->find($id);

        if (!$item)
        {
            throw new \Exception('Бренда с таким ID не существует');
        }

        return $item;
    }

    public function create($name)
    {
        $attributes = [
            'name' => $name
        ];

        $item = $this->model->query()->firstOrCreate($attributes);
        return $item;
    }


    public function update($attributes, $id)
    {

        $item = $this->find($id);
        return $item->update($attributes);
    }

    public function getAll()
    {
        $items =  $this->model->query()
            ->get();

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
