<?php

namespace App\Services;

use App\Models\ParseStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParseStatisticService
{
    public function __construct(protected ParseStatistic $model){}

    public function getByUserNotEnd(){
        $items =  $this->model->query()
            ->where('user_id','=',Auth::user()->id)
            ->where('end_parse','=',null)
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
            ->with('user')
            ->with('user.company')
            ->when(!empty($request->start),function ($q) use ($request){
                $q->where('created_at','>',$request->start.' 00:00:00');
            })
            ->when(!empty($request->end),function ($q) use ($request){
                $q->where('created_at','<=',$request->end.' 23:59:59');
            })
            ->when(!empty($request->user_id),function ($q) use ($request){
                $q->where('user_id','=',$request->user_id);
            })
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
