<?php

namespace App\Services;

use App\Models\CatalogCharacteristicItem;
use App\Models\CatalogItem;
use App\Models\ProductItem;
use App\Models\UserCart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CartService
{

    public static function getCart()
    {
        //session()->put("cart", []);
        $count = 0;
        $price = 0;
        $items = [];
        foreach (session()->get("cart", []) as $k => $i) {
            $item = CatalogItem::query()->find($i["id"]);
            if (!empty($item) and !empty($i["count"])) {
                $count += (int)$i["count"];
                if (!empty($i["size"])) {
                    $item->key = "id{$i["id"]}size{$i["size"]}color{$i["color"]}";
                    $item->size = $i["size"];
                } else {
                    $item->key = "id{$i["id"]}size0";
                    $item->size = 0;
                }


                $size = CatalogCharacteristicItem::where('catalog_characteristic_id','=',16)
                    ->where('name_tr','=',$i["size"])
                    ->orWhere('name_ru','=',$i["size"])
                    ->orWhere('name_kz','=',$i["size"])
                    ->first();
                $image = ProductItem::where('item_id','=',$i["id"])
                            ->where('size','=',$size->id)
                            ->where('color','=',$i["color"])
                            ->first();

                if (!empty($i["color"])) {
                    $item->key = "id{$i["id"]}size{$i["size"]}color{$i["color"]}";
                    $item->color = $i["color"];
                } else {
                    $item->key = "id{$i["id"]}size0color0";
                    $item->color = 0;
                }
                if (!empty($i["size"])) {
                    $item->key = "id{$i["id"]}size{$i["size"]}";
                    $item->size = $i["size"];
                } else {
                    $item->key = "id{$i["id"]}size0";
                    $item->size = 0;
                }

                if (!empty((float)$item->getAttribute("price_sale"))) {
                    $p = (float)$item->getAttribute("price_sale");
                } else {
                    $p = (float)$item->getAttribute("price");
                }

                if ($item->sale > 0){
                    $p = $p - ($p * $item->sale / 100);
                }

                $thisPrice = $p * (int)$i["count"];
                $price += $thisPrice;
                $item->price = number_format($p, 2, ".", " ");
                $item->count = $i["count"];
                $item->total = number_format($thisPrice, 2, ".", " ");
                $item->image = $image->image ?? null;
                $items[] = $item;
            }
        }
        $price = number_format($price, 2, ".", " ");

        return compact("count", "price", "items");
    }

    public static function add($id, $count, $size = 0,$color = 0)
    {
        $cart = session()->get("cart");
        $key = "id{$id}size{$size}color{$color}";

        if (!empty($cart[$key])) {
            $count = $cart[$key]["count"] + (int)$count;
        }else{
            $count = 1;
        }

        $sizeId = CatalogCharacteristicItem::select('id')
            ->where('catalog_characteristic_id','=',16)
            ->where('name_tr','=',request()->get("size"))
            ->orWhere('name_ru','=',request()->get("size"))
            ->orWhere('name_kz','=',request()->get("size"))
            ->get()->toArray();

        $result = [];
        array_walk_recursive($sizeId, function ($item, $key) use (&$result) {
            $result[] = $item;
        });


        $item = ProductItem::where('item_id','=',request()->get("id"))
            ->whereIn('size',$result)
            ->where('color','=',request()->get("color"))
            ->first();


        if ($item->count - ($count ?? 1)  < 0){
            return 422;
        }

        $cart[$key] = compact("id", "count", "size","color");
        session()->put("cart", $cart);
        return self::getCart(false);
    }

    public static function set($key, $count)
    {

        $thisPrice = 0;
        $cart = session()->get("cart");

        if ($count == 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]["count"] = $count;
            $item = CatalogItem::query()->find($cart[$key]["id"]);

            $sizeId = CatalogCharacteristicItem::where('catalog_characteristic_id','=',16)
                ->where('name_tr','=',$cart[$key]["size"])
                ->orWhere('name_ru','=',$cart[$key]["size"])
                ->orWhere('name_kz','=',$cart[$key]["size"])
                ->first();

            $item = ProductItem::where('item_id','=',$cart[$key]["id"])
                ->where('size','=',$sizeId->id)
                ->where('color','=',$cart[$key]["color"])
                ->first();

            if ($item->count - $cart[$key]["count"] < 0){
                return 422;
            }

            $p = 0;
            if (!empty((float)$item->getAttribute("price_sale"))) {
                $p = (float)$item->getAttribute("price_sale");
            } else {
                $p = (float)$item->getAttribute("price");
            }
            $thisPrice = $p * $count;
        }
        session()->put("cart", $cart);
        return array_merge(["price_this" => number_format($thisPrice, 2, ".", " ")], self::getCart(false));
    }

    public static function clear()
    {

        session()->put("cart", []);
        session()->put("deliveryCalDate", []);
        session()->put("deliveryPrices", []);
    }

/*    public static function count($id)
    {
        $cart = session()->get("cart");
        return $cart[$id]["count"] ?? 0;
    }*/

    /*public static function createOrder($data)
    {
        //$data = session()->get("order_data");
        $cart = self::getCart(true);
        $date = date('d.m.Y H:i', strtotime("+7 hours"));
        $bonusUse = $data["bonus"] ?? 0;
        $price = (float)$cart["price"];
        if ($bonusUse > $price) {
            $bonusUse = $price;
        }
        $txt = [
            "Заказ от " . $date,
            "Имя: " . $data["name"],
            "Телефон: " . $data["phone"],
            "Email: " . $data["email"],
            "Улица: " . $data["street"],
            "Дом: " . $data["house"],
            "Квартира/офис: " . $data["apartment"],
            "Комментарии: " . $data["comment"],
            "Способ доставки: " . $data["type"],
            "Точка самовывоза: " . @$data["from"] ?? "",
            "Способ оплаты: " . $data["pay"],
            "Почта адрес: " . $data["post_address"],
            "Почта цена доставки: " . $data["post_price"],
            "Почта срок: " . $data["post_day"],
        ];

        $txt[] = "";
        if (!empty($cart["items"]))
            foreach ($cart["items"] as $k => $i) {
                if (!empty($i->size->name)) {
                    $txt[] = "{$i->name} ({$i->size->name}) / {$i->count} шт. по {$i->price}р. / {$i->total}р.";
                } else {
                    $txt[] = "{$i->name} / {$i->count} шт. по {$i->price}р. / {$i->total}р.";
                }
            }
        $txt[] = "Всего: {$cart["price"]} р.";
        $txt[] = "Истользовано бонусов: {$bonusUse}";
        $txt[] = "Итого: " . ($price - $bonusUse) . " р.";

        SendTelegramMessage::dispatch("-1001209265314", "1724706909:AAHMNFvchCO6qCmqhTyt788DcUXOG5208uY", $txt);
        Order::addOrder($cart, $data);
    }*/
}
