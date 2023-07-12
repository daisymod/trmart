<?php

namespace App\Services;

use App\Models\Feedback;
use App\Models\ItemCompound;
use Illuminate\Support\Facades\Auth;

class FeedbackService
{
    public function __construct(protected Feedback $model){}


    private function find($id) : object
    {
        $item = $this->model->query()->find($id);

        if (!$item)
        {
            throw new \Exception('Запись с таким ID не существует');
        }

        return $item;
    }

    public function create($attributes,$id)
    {
        $attributes['user_id'] = Auth::user()->id ?? 0;
        $attributes['item_id'] = $id;
        $attributes['rating'] = $attributes['rating'] ?? 1;
        $item = $this->model->query()->create($attributes);

        return $item;
    }


    public function update($attributes, $id)
    {
        $item = $this->find($id);
        return $item->update($attributes);
    }

    public function getAll($id)
    {
        return $this->model->query()
            ->where('item_id','=',$id)
            ->with('user')
            ->with('user.company')
            ->orderByDesc('id')
            ->get();
    }


    public function ratingById($id){
        return $this->model->query()
            ->whereHas('user',function ($q){
                $q->where('role','=','user');
            })
            ->where('item_id','=',$id)
            ->avg('rating');
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



    public function getCompanyFeedback($user_id){
        return $this->model->query()
            ->whereHas('item',function($query) use ($user_id){
                $query->where('user_id','=',$user_id);
            })
            ->whereHas('user',function ($q){
                $q->where('role','=','user');
            })
            ->count();
    }


    public function getCompanyFeedbackAvg($user_id){
        return $this->model->query()
            ->whereHas('item',function($query) use ($user_id){
                $query->where('user_id','=',$user_id);
            })
            ->whereHas('user',function ($q){
                $q->where('role','=','user');
            })
            ->avg('rating');
    }
}
