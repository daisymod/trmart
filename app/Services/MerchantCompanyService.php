<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;

class MerchantCompanyService
{
    public function __construct(protected Company $model){}


    private function find($id) : object
    {
        $company = $this->model->query()->find($id);

        return $company;
    }

    public function create($attributes)
    {
        $attributes['company_name'] = $attributes['company'];
        $attributes['user_id'] = Auth::user()->id;
        $company = $this->model->query()->create($attributes);

        return $company;
    }


    public function update($attributes, $id)
    {
        $company = $this->model::query()
            ->where('user_id','=',$id)
            ->first();
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
