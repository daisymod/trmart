<?php

namespace App\Services;


use App\Models\UserShowProduct;
use Illuminate\Support\Facades\Auth;

class UserShowProductService
{
    public function __construct(protected UserShowProduct $model){}


    private function find($id) : object
    {
        $item = $this->model->query()->find($id);

        if (!$item)
        {
            throw new \Exception('Запись с таким ID не существует');
        }

        return $item;
    }

    public function create($id)
    {

        $attributes['product_id'] = $id;
        $attributes['session'] = session()->get('_token');
        $attributes['user_id'] = Auth::user()->id ?? 0;


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
        $products = $this->model->query()
            ->when(Auth::check(),function ($query){
                $query->where('user_id','=',Auth::user()->id);
            })
            ->when(!Auth::check(),function ($query){
                $query->where('session','=',session()->get('_token'));
            })
            ->with('product')
            ->whereHas('product',function ($q){
                $q->where("status",'=', 2);
                $q->where("active",'=', "Y");
            })
            ->orderByDesc('id')
            ->limit(20)
            ->get();

         return $products;
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
        $city = $this->find($id);

        return $city->delete();
    }






}
