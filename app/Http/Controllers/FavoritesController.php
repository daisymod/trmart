<?php

namespace App\Http\Controllers;

use App\Models\Favorites;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FavoritesController
{
    public function addFavorites(Request $request)
    {
        $item = Favorites::query()->where(['catalog_items_id' => $request->input('item'), 'user_id' => Auth::user()->id]);

        if ($item->count()) {
            Favorites::query()->where('id', $item->value('id'))->delete();
            return ['status' => 0];
        } else {
            $favorite = new Favorites();
            $favorite->catalog_items_id = $request->input('item');
            $favorite->user_id = Auth::user()->id;
            $favorite->save();
            if ($favorite->id) {
                return ['status' => 1];
            } else {
                return ['status' => 'Ошибка'];
            }

        }
    }
}
