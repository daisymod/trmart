<?php

namespace App\Services;

use App\Models\ParseStatistic;

class ParseStatisticService
{
    public function __construct(protected ParseStatistic $model){}


    private function find($id) : object
    {
        $item = $this->model->query()->find($id);

        if (!$item)
        {
            throw new \Exception('ParseStatistic с таким ID не существует');
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
