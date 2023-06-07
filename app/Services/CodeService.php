<?php

namespace App\Services;

use App\Models\Code;
use App\Models\Color;

class CodeService
{
    public function __construct(protected Code $model){}


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


    public function getById($id,$code)
    {
        return $this->model->query()
            ->where('phone','=',$id)
            ->where('code','=',$code)
            ->first();
    }

    public function getByIdSms($code)
    {
        return $this->model->query()
            ->where('code','=',$code)
            ->first();
    }

    public function show($id)
    {
        return $this->find($id);
    }


    public function delete($id)
    {
        return $this->model->query()
            ->where('phone','=',$id)
            ->delete();
    }




}
