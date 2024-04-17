<?php

namespace App\Services;

use App\Models\CompanyEmployee;

class MerchantCompanyEmployeeService
{
    public function __construct(protected CompanyEmployee $model){}


    private function find($id) : object
    {
        $company = $this->model->query()->find($id);

        return $company;
    }

    public function create($attributes)
    {
        $company = $this->model->query()->create($attributes);

        return $company;
    }


    public function update($attributes, $id)
    {
        $company = $this->find($id);
        return $company->update($attributes);
    }

    public function getAll()
    {
        return $this->model->query()
            ->get();
    }


    public function show($id)
    {
        return $this->model
            ->query()
            ->find($id);
    }


    public function delete($id)
    {
        $company = $this->find($id);

        return $company->delete();
    }



}
