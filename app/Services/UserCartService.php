<?php

namespace App\Services;

use App\Models\UserCart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCartService
{
    public function __construct(protected UserCart $model){}


    private function find($id) : object
    {
        $item = $this->model->query()->find($id);

        return $item;
    }

    public function create($attributes)
    {
        $attributes['items'] =  '['.implode(' ',$attributes['items']).']';
        $attributes['price'] = str_replace(" ", '',$attributes['price']);
        $attributes['user_id'] = Auth::user()->id ?? 0;
        $item = $this->model->query()->create($attributes);

        return $item;
    }


    public function update($attributes, $id)
    {
        $item = $this->find($id);
        $attributes['is_checked'] = 1;
        return $item->update($attributes);
    }

    public function getAll(Request $request)
    {
        return $this->model->query()
            ->when(Auth::user()->role == 'merchant',function ($q){
                $q->where('items','LIKE','%user_id":'.Auth::user()->id.'%');
            })
            ->when(!empty($request->to),function ($q) use ($request){
                $q->whereBetween('created_at',[Carbon::parse($request->from)->format('Y-m-d h:i:s'),Carbon::parse($request->to)->addDays(1)->format('Y-m-d h:i:s')]);
            })
            ->with('user')
            ->get();
    }


    public function getById($id)
    {
        return $this->model->query()
            ->where('user_id','=',$id)
            ->get();
    }


    public function show($id)
    {
        return $this->model->query()->where('id','=',$id)
                ->first();
    }


    public function delete($id)
    {
        $item = $this->model->query()->where('user_id','=',$id);

        return $item->delete();
    }


    public function deleteById($id)
    {
        $item = $this->model->query()->where('id','=',$id);

        return $item->delete();
    }


}

