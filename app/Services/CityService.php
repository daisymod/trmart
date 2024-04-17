<?php

namespace App\Services;

use App\Models\City;

class CityService
{
    public function __construct(protected City $model){}


    private function find($id) : object
    {
        $city = $this->model->query()->find($id);

        if (!$city)
        {
            throw new \Exception('Города с таким ID не существует');
        }

        return $city;
    }

    public function create($attributes,$id)
    {
        $attributes['country_id'] = $id;
        $city = $this->model->query()->create($attributes);

        return $city;
    }


    public function update($attributes, $id)
    {
        $city = $this->find($id);
        return $city->update($attributes);
    }

    public function getAll($attributes)
    {
        $perPage = data_get($attributes, 'per_page', 10);

        return $this->model->query()
            ->when(!empty($attributes['country_id']),function ($query) use($attributes){
                $query->where('country_id', $attributes['country_id']);
            })
            ->paginate($perPage);
    }


    public function getById($id)
    {
        return $this->model->query()
             ->where('country_id', $id)
            ->get();
    }


    public function show($id)
    {
        return $this->find($id);
    }


    public function delete($id)
    {
        $city = $this->find($id);

        return $city->delete();
    }


}
