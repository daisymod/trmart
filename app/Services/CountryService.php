<?php

namespace App\Services;

use App\Models\Country;

class CountryService
{
    public function __construct(protected Country $model){}


    private function find($id) : object
    {
        $country = $this->model->query()->find($id);

        return $country;
    }

    public function create($attributes)
    {
        $country = $this->model->query()->create($attributes);

        return $country;
    }


    public function update($attributes, $id)
    {
        $country = $this->find($id);
        return $country->update($attributes);
    }

    public function getAll()
    {
        return $this->model->query()
            ->with('city')
            ->get();
    }


    public function show($id)
    {
        return $this->model
            ->query()
            ->with('city')
            ->find($id);
    }


    public function delete($id)
    {
        $country = $this->find($id);

        return $country->delete();
    }



}
