<?php

namespace App\Services;

use App\Models\Color;

class ColorService
{
    public function __construct(protected Color $model){}


    private function find($id) : object
    {
        $color = $this->model->query()->find($id);

        if (!$color)
        {
            throw new \Exception('Цвет с таким ID не существует');
        }

        return $color;
    }

    public function create($attributes)
    {
        $color = $this->model->query()->create($attributes);

        return $color;
    }


    public function update($attributes, $id)
    {
        $color = $this->find($id);
        return $color->update($attributes);
    }

    public function getAll()
    {
        return $this->model->query()
            ->get();
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
        $color = $this->find($id);

        return $color->delete();
    }


}
