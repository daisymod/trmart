<?php

namespace App\Services;

use App\Models\Compound;

class CompoundModelService
{
    public function __construct(protected Compound $model){}


    private function find($id) : object
    {
        $item = $this->model->query()->find($id);

        if (!$item)
        {
            throw new \Exception('Бренда с таким ID не существует');
        }

        return $item;
    }

    public function create($attributes): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
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
