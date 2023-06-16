<?php

namespace App\Http\Controllers;

use App\Models\CatalogCatalogCharacteristic;
use App\Models\CatalogCharacteristic;
use App\Models\CatalogItem;
use App\Models\CatalogItemDynamicCharacteristic;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\User;
use App\Services\CurrencyService;
use App\Services\FeedbackService;
use App\Services\LanguageService;
use App\Services\RatesService;
use App\Services\ShopService;
use App\Services\UserShowProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Lang;

class ShopController
{

    public function __construct(public CurrencyService $currency, public RatesService $rates,public FeedbackService $feedback,public UserShowProductService $showProductService)
    {
    }

    public function getColorAndSizeById(Request $request){
        $data = ProductItem::select('size')
                ->where('item_id','=',str_replace('.html','',$request->item_id))
                ->where('color','=',$request->color)
                ->where('count','>',0)
                ->get()->toArray();

        $size = ProductItem::where('item_id','=',str_replace('.html','',$request->item_id))
            ->with('sizeData')
            ->whereHas('sizeData',function ($q){
            })
            ->get()->unique('size');
        $html = '';
        $result = [];
        array_walk_recursive($data, function ($item, $key) use (&$result) {
            $result[] = $item;
        });

        $index = 1;
        $checked = true;
        foreach ($size as $item){

            if (!in_array($item->sizeData->id,$result)){
                $html .= '<div class="radio-wrap not-active" data-size="'.$item->sizeData->id  .'">
                                    <input type="radio" disabled value="'.$item->sizeData->{'name_'.$request->lang}  .'" id="radio'.$index.'" name="size">
                                    <label for="radio'.$index.'">
                                        <b>'.$item->sizeData->{'name_'.$request->lang}  .'</b>
                                    </label>
                                </div>';
            }else{
                $html .= '<div class="radio-wrap" data-size="'.$item->sizeData->id  .'">
                                    <input '.$this->checkChecked($checked).' type="radio" value="'.$item->sizeData->{'name_'.$request->lang}  .'" id="radio'.$index.'" name="size">
                                    <label for="radio'.$index.'">
                                        <b>'.$item->sizeData->{'name_'.$request->lang}  .'</b>
                                    </label>
                                </div>';

                $checked = false;
            }



            $index ++;
        }

        return response()->json($html
            , 201);
    }

    public function checkChecked($flag){
        if ($flag == true){
            return 'checked';
        }else{
            return '';
        }
    }

    public function actionList(Request $request, $id)
    {
        $checkProducts = $this->showProductService->getAll();
        return view("shop.list", ShopService::actionList($request,$id),['checkProducts' => $checkProducts]);
    }

    public function actionItem($id)
    {

        $checkProducts = $this->showProductService->getAll();

        $feedback = $this->feedback->getAll($id);
        $rating = $this->feedback->ratingById($id);
        $currency = $this->currency->getCurrencyById(Cookie::get('currency'));
        $turkeyCurrency = $this->currency->getTurkeyCurrency();
        $coefficient =  Cookie::get('currency') == 2 ? 1 : $this->rates->getRateTurkey(Cookie::get('currency'));
        $compact  = ShopService::actionItem($id);


        $size = ProductItem::where('item_id','=',$id)
            ->with('sizeData')
            ->whereHas('sizeData',function ($q){
                //$q->where('name_tr','!=','');
            })
            ->get()->unique('size');

        $color = ProductItem::where('item_id','=',$id)
            ->with('colorData')
            ->whereHas('colorData',function ($q){
                //$q->where('name_tr','!=','');
            })
            ->get()->unique('color');


        $colorCurrent = $color[0]->{"name_".app()->getLocale()} ;


        foreach ($size as $item){
            $item->sizeData->exist = $this->checkExist($item->sizeData->id,$color[0]->color,$id);
        }

        foreach ($color as $item){
            $image = json_decode($item->colorData->image, true);
            if (!empty($image)) {
                $item['img'] =  $image[0]["img"];
            } else {
                $item['img'] = "/i/no_image.png";
            }
        }

        $characteristic = CatalogItemDynamicCharacteristic::where('item_id','=',$id)
                ->with('category')
                ->get();

        $company = User::where('id','=',$compact['record']->user_id)
            ->first();

        if ($compact['record']->user_id != 0){
            $countCompanyFeedback = $this->feedback->getCompanyFeedback($compact['record']->user_id);
            $avgCompanyFeedback = $this->feedback->getCompanyFeedbackAvg($compact['record']->user_id);
        }
        $this->showProductService->create($id);


        if (Auth::check()){
            if ($compact['record']['user_id'] == Auth::user()->id || Order::where('user_id','=',Auth::user()->id)
                    ->whereHas('items',function ($q) use ($id){
                        $q->where('catalog_item_id','=',$id);
                    })
                    ->count() > 0 ){
                $canAddNewFeedback = true;
            }else{
                $canAddNewFeedback = false;
            }
        }else{
            $canAddNewFeedback = false;
        }

        return view("shop.item",array_merge($compact,['canAddNewFeedback' =>$canAddNewFeedback,'colorCurrent' =>$colorCurrent,'checkProducts' => $checkProducts, 'color' => $color, 'size' => $size, 'characteristic'=> $characteristic, 'company' => $company,'avgCompanyFeedback' => $avgCompanyFeedback ?? 0, 'countCompanyFeedback' => $countCompanyFeedback ?? 0,  'count_feedback' =>count($feedback),'rating' => $rating, 'feedback' => $feedback, 'currency' => $currency,'turkeyCurrency'=> $turkeyCurrency,'coefficient'=> $coefficient]));
    }


    public function checkExist($size,$color,$id){

        $exist = ProductItem::where('size','=',$size)
            ->where('color','=',$color)
            ->where('item_id','=',$id)
            ->where('count','>',0)
            ->first();

        if (!empty($exist->id)){
            return true;
        }else{
            return  false;
        }

    }

    public function actionFind()
    {

        $checkProducts = $this->showProductService->getAll();
        $compact  = ShopService::actionFind(request()->get("find", ""));
        return view("shop.find", array_merge($compact,['checkProducts' =>$checkProducts]));
    }


    public function search(Request $request)
    {
        if (empty($request->find)){
            $items = [];
        }else{
            $items = CatalogItem::query()
                ->where("status", 2)
                ->where("active", "Y")
                ->where("name_" . $request->lang, "LIKE", "%$request->find%")
                ->where("brand", "LIKE", "%$request->brand%")
                ->limit(5)
                ->get();
        }



        return response()->json(
            [
                'items' => $items
            ],
            201
        );
    }



    public function getColorBySize(Request $request){

    }

}
