<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\CatalogMerchant;
use Illuminate\Support\Facades\Auth;

class CatalogMerchantService
{
    public function __construct(protected CatalogMerchant  $model){}


    private function find($id) : object
    {
        $item = $this->model->query()->find($id);

        if (!$item)
        {
            throw new \Exception('Запись с таким ID не существует');
        }

        return $item;
    }

    public function create($item)
    {
        $attributes = [];
        $attributes['catalog_id'] = $item;
        $attributes['merchant_id'] = Auth::user()->id;

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


    public function delete()
    {
        return $this->model::query()->where('merchant_id','=',Auth::user()->id)
            ->delete();

    }


}
