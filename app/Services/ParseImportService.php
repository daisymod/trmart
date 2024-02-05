<?php

namespace App\Services;

use App\Models\ParseImport;
use App\Models\ParseStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParseImportService
{
    public function __construct(protected ParseImport $model){}

    public function getByUserNotEnd(){
        $items =  $this->model->query()
            ->first();

        return $items;
    }

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

    public function getAll($request)
    {
        $items =  $this->model->query()
            ->with(['user','catalog'])
            ->orderByDesc('id')
            ->paginate(100);

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
