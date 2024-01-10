<?php

namespace App\Http\Controllers;

//Illuminate
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
//App

use App\Http\Requests\CustomerRequest;
use App\Forms\ExtendCustomerSelfForm;
use App\Http\Resources\OrderInfoResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\FavoriteResource;
use App\Http\Resources\OrdersResource;
use App\Forms\CustomerSelfForm;
use App\Models\OrderReviews;
use App\Models\CatalogItem;
use App\Models\KPLocation;
use App\Models\KPPostCode;
use App\Models\OrderItem;
use App\Models\Favorites;
use App\Models\Customer;
use App\Models\Country;
use App\Models\Catalog;
use App\Models\Order;
use App\Models\City;

class CustomerController extends Controller
{
    public function actionSelfGet(): Factory|View|Application
    {
        $record = Customer::query()->findOrFail(Auth::user()->id);
        Gate::authorize("customer-edit", $record);
        $form = new ExtendCustomerSelfForm($record);
        $form = $form->formRenderEdit();
        $countries = Country::query()
            ->select('id', 'name_ru')
            ->get()
            ->values();
        if ($record->country_id === 3) {
            $regions   = KPLocation::query()
                ->orderBy('name')
                ->where('parent_id', 0)
                ->select('id', 'name')
                ->get()
                ->values();
            $areas     = KPLocation::query()
                ->orderBy('name')
                ->where('parent_id', KPLocation::query()->where('id', $record->area_id)->value('parent_id'))
                ->select('id', 'name')
                ->get()
                ->values();
            $cities    = KPLocation::query()
                ->orderBy('name')
                ->where('parent_id', KPLocation::query()->where('id', $record->city_id)->value('parent_id'))
                ->select('id', 'name')
                ->get()
                ->values();
            $postCodes = KPPostCode::query()
                ->orderBy('title')
                ->where('kp_locations_id', KPPostCode::query()->where('id', $record->postcode_id)->value('kp_locations_id'))
                ->select('id', 'title')
                ->get()
                ->values();
        } else {
            $regions   = null;
            $areas     = null;
            $postCodes = null;
            $cities    = null;
        }
        return view("customer.self", compact("record", "form", "countries", "cities", "regions", "areas", "postCodes"));
    }

    public function actionSelfPost(CustomerRequest $request): array
    {
        $record = Customer::query()->findOrFail(Auth::user()->id);
        Gate::authorize("customer-edit", $record);
        $form = new CustomerSelfForm($record);
        $form->formSave($request->all());
        return ["redirect" => route("customer.self", ["send" => true])];
    }

    public function actionFavoritesGet(Request $request): View|Factory|JsonResponse|Application
    {
        $record = Customer::query()->findOrFail(Auth::user()->id);

        // Favorites Query
        $items = Favorites::query()->where('user_id', Auth::user()->id);

        // Favorites Category
        $catalogCategories = CatalogItem::query()
            ->whereIn('id', $items->pluck('catalog_items_id')->toArray())
            ->whereNotIn('catalog_id', array('0'))
            ->pluck('catalog_id')
            ->toArray();
        $categories = Catalog::query()->whereIn('id', $catalogCategories)
            ->select('id', 'name_ru', 'name_kz', 'name_tr')
            ->get()
            ->toArray();

        // Favorites Collection
        $data = FavoriteResource::collection($items->get())->resolve();

        // Brands
        $brands = CatalogItem::query()
            ->whereIn('id', $items->pluck('id')->toArray())
            ->select('brand')
            ->groupBy('brand')
            ->orderBy('brand')
            ->get();

        // Authorize
        Gate::authorize("customer-favorites", $record);

        if ($request->input('brand') && $request->input('category')) {
            return match ([$request->input('category'), $request->input('brand')]) {
                ['all', 'all'] => response()->json(collect($data)->values()),
                ['all', $request->input('brand')] => response()->json(collect($data)->where('brand', $request->input('brand'))->values()),
                [$request->input('category'), 'all'] => response()->json(collect($data)->where('catalog_id', $request->input('category'))->values()),
                default => response()->json(collect($data)->where('catalog_id', $request->input('category'))->where('brand', $request->input('brand'))->values()),
            };
        }

        return view("customer.favorites", compact("record", "data", "categories", "brands"));
    }

    public function num_decline( $number, $titles, $show_number = true ){

        if( is_string( $titles ) ){
            $titles = preg_split( '/, */', $titles );
        }

        // когда указано 2 элемента
        if( empty( $titles[2] ) ){
            $titles[2] = $titles[1];
        }

        $cases = [ 2, 0, 1, 1, 1, 2 ];

        $intnum = abs( (int) strip_tags( $number ) );

        $title_index = ( $intnum % 100 > 4 && $intnum % 100 < 20 )
            ? 2
            : $cases[ min( $intnum % 10, 5 ) ];

        return ( $show_number ? "$number " : '' ) . $titles[ $title_index ];
    }

    public function actionOrdersGet(Request $request): View|Factory|JsonResponse|Application
    {
        $record = Customer::query()->findOrFail(Auth::user()->id);
        $orders = Order::query()->with(['items'])
            ->when(!empty($request->orders_status) && ($request->orders_status != 'all'),function ($q) use ($request){
                $q->where('status','=',$request->orders_status);
            })
            ->where('user_id', Auth::user()->id)
            ->whereNotIn('status', array(0))
            ->orderByDesc('id')->get();

        foreach ($orders as $order){

            //* $order->items[0]->count;
            $order->delivery_sum = ($order->delivery_price ?? 0) + ($order->tr_delivery_price ?? 0);
            $order->delivery_dt_end = Carbon::parse($order->created_at)->addDays(15)->format('Y-m-d');
            $order->left = Carbon::parse($order->created_at)->addDays(15)->diffInDays(Carbon::now());
            $order->left = $order->left < 0 ? 0 : $order->left;
            $order->left = $this->num_decline( $order->left, [trans('system.day'), trans('system.day1'), trans('system.day2')] );
        }

        $data = OrdersResource::collection($orders)->resolve();
        Gate::authorize("customer-orders", $record);

        if ($request->input('status')) {
            $status = $request->input('status');
            return match ($status) {
                'all' => response()->json(collect($data)->values()),
                $status => response()->json(collect($data)->where('status', $status)->values())
            };
        }

        return view("customer.orders", compact("record", "data"));
    }

    public function actionOrderItemGet($id): Factory|View|Application
    {
        $record = Customer::query()->findOrFail(Auth::user()->id);
        $order = OrderInfoResource::collection(Order::query()->where(['user_id' => Auth::user()->id, 'id' => $id])->get())->resolve();
        $items = OrderItemResource::collection(OrderItem::query()->where('order_id', $id)->get())->resolve();

        //Gate::authorize("customer-orders", $record);

        return view("customer.order_item", compact("record", "order", "items"));
    }

    public function actionCanceledOrder($id)
    {
        Order::query()->where('id', $id)->update(['status' => 0]);

        return redirect()->route('customer.orders');
    }

    public function actionDeleteFavoritesItem($id): Redirector|Application|RedirectResponse
    {
        $record = Favorites::query()->findOrFail($id);
        Gate::authorize("customer-del", $record);
        $record->delete();
        return redirect(route("customer.favorites"));
    }

    public function actionDeleteFavoritesAll($userId): Redirector|Application|RedirectResponse
    {
        $record = Favorites::query()->where('user_id', $userId)->delete();
        Gate::authorize("customer-del-all", $record);
        return redirect(route("customer.favorites"));
    }

    public function actionReview(Request $request)
    {
        $review = new OrderReviews();
        $review->order_id = $request->input('id');
        $review->user_id  = Auth::user()->id;
        $review->subject  = $request->input('text');
        $review->rating   = $request->input('rating');
        $review->save();

        return response()->json(['status' => true]);
    }
}
