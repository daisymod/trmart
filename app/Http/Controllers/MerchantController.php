<?php

namespace App\Http\Controllers;

use App\Actions\MerchantListAction;
use App\Exports\OrderExport;
use App\Forms\ExtendMerchantAdminForm;
use App\Forms\ExtendMerchantSelfForm;
use App\Forms\MerchantAdminForm;
use App\Forms\MerchantRegForm;
use App\Forms\MerchantSelfForm;
use App\Http\Requests\MerchantEditPostRequest;
use App\Http\Requests\MerchantRegPostRequest;
use App\Http\Requests\MerchantSelfPostRequest;
use App\Http\Requests\UserRegFromPostRequest;
use App\Http\Requests\UserRegSMSPostRequest;
use App\Http\Resources\OrderInfoResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrdersResource;
use App\Mail\RejectNewItemMail;
use App\Mail\RejectVerificationMerchantMail;
use App\Models\CatalogCharacteristicItem;
use App\Models\CatalogItem;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\CurrencyRate;
use App\Models\Customer;
use App\Models\KPLocation;
use App\Models\KPPostCode;
use App\Models\Merchant;
use App\Models\MerchantKey;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\TRLocation;
use App\Models\TurkeyRegion;
use App\Models\User;
use App\Services\CatalogMerchantService;
use App\Services\MerchantCompanyEmployeeService;
use App\Services\MerchantCompanyService;
use App\Services\SendMailService;
use App\Services\UserLoginService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class MerchantController extends Controller
{

    public function __construct(protected CatalogMerchantService $catalogMerchant,protected UserService $user, protected MerchantCompanyService $company,protected UserLoginService $login,protected MerchantCompanyEmployeeService $employee)
    {
    }

    public function actionList(MerchantListAction $action)
    {
        Gate::authorize("merchant-list");
        return view("merchant.list", $action());
    }

    public function actionEditGet($id)
    {
        $record = Merchant::query()->findOrFail($id);
        $company = Company::where('user_id','=',$id)
            ->first();

        $record->first_name = $company->first_name;
        $record->last_name = $company->last_name;
        $record->patronymic = $company->patronymic;
        $record->company_name = $company->company_name;
        $record->shop_name = $company->shop_name;

        $record->tax_office = $company->tax_office;

        $record->legal_address_city = $company->legal_address_city;
        $record->legal_address_street = $company->legal_address_street;
        $record->legal_address_number = $company->legal_address_number;
        $record->legal_address_office = $company->legal_address_office;

        $record->city = $company->city;
        $record->street = $company->street;
        $record->number = $company->number;
        $record->office = $company->office;

        if (($record->legal_address_street == $record->street)
            && ($record->legal_address_city == $record->city)
            && ($record->legal_address_office == $record->office)
            && ($record->legal_address_number = $record->number))
            $equal = 1;
        else
            $equal = 0;

        $countries = Country::query()
            ->select('id', 'name_ru')
            ->get()
            ->values();

        $area = TurkeyRegion::all();

        if (Auth::user()->country_id === 1) {
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
            $cities = City::query()->where('country_id', $record->country_id)->pluck('name_ru', 'id');
        }


        $legalAddressCity = Company::query()->where('user_id', $id)->value('legal_address_city');
        $addressCity = Company::query()->where('user_id', $id)->value('city');


        Gate::authorize("merchant-edit", $record);
        $form = new ExtendMerchantAdminForm($record);
        $trAreas = TRLocation::query()->select('name', 'id')->orderBy('name')->get();

        $form = $form->formRenderEdit();
        return view("merchant.edit", compact("record", 'equal',"area","form", "countries", "cities", "regions", "areas", "postCodes", "trAreas", "legalAddressCity", "addressCity"));
    }

    public function actionEditPost($id, MerchantEditPostRequest $request)
    {

        $record = Merchant::query()->findOrFail($id);
        Gate::authorize("merchant-edit", $record);
        $this->company->update($request->all(),$id);
        $this->user->updateMerchant($request->all(),$id);


        if ($request->status == 3 && !empty($request->reason) && Auth::user()->role == 'admin'){
            $user = User::where('id','=',$id)
                ->first();
            Mail::to($user->email)->send(new RejectVerificationMerchantMail($user, $request->all()));
        }

        return ["redirect" => route("merchant.list")];
    }

/*    public function actionRegGet()
    {
        $record = new MerchantRegForm(new Merchant());
        $form = $record->formRenderAdd();
        return view("merchant.reg", compact("form"));
    }*/

    public function actionRegPost(MerchantRegPostRequest $request)
    {
        UserLoginService::putRegData($request->all());
        return $request->all();
    }

    public function actionRegSMSGet()
    {
        return view("user.sms");
    }

    public function actionRegSMSPost(UserRegSMSPostRequest $request)
    {
        $user = $this->login->registerMerchant($request->all());

        $companyData = $this->company->create($request->all());

        $data = [
            'role' => 'merchant',
            'user_id' => $user->id,
            'company_id' => $companyData->id
        ];
        $this->employee->create($data);

        //MerchantRegForm::reg(UserLoginService::getRegData());
        //return redirect(route("user.lk"));
        return ["redirect" => route("user.lk")];
    }

    public function actionSelfGet()
    {
        $record = Merchant::query()->findOrFail(Auth::user()->id);
        $company = Company::where('user_id','=',Auth::user()->id)
            ->first();

        $record->first_name = $company->first_name;
        $record->last_name = $company->last_name;
        $record->patronymic = $company->patronymic;
        $record->company_name = $company->company_name;
        $record->shop_name = $company->shop_name;

        $record->tax_office = $company->tax_office;

        $record->legal_address_city = $company->legal_address_city;
        $record->legal_address_street = $company->legal_address_street;
        $record->legal_address_number = $company->legal_address_number;
        $record->legal_address_office = $company->legal_address_office;

        $record->city = $company->city;
        $record->street = $company->street;
        $record->number = $company->number;
        $record->office = $company->office;

        if (($record->legal_address_street == $record->street)
            && ($record->legal_address_city == $record->city)
            && ($record->legal_address_office == $record->office)
            && ($record->legal_address_number = $record->number))
            $equal = 1;
        else
            $equal = 0;

        $countries = Country::query()
            ->select('id', 'name_ru')
            ->get()
            ->values();

        $area = TurkeyRegion::all();

        if (Auth::user()->country_id === 1) {
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
            $cities = City::query()->where('country_id', $record->country_id)->pluck('name_ru', 'id');
        }

        if (Auth::check()) {
            $legalAddressCity = Company::query()->where('user_id', Auth::user()->id)->value('legal_address_city');
            $addressCity = Company::query()->where('user_id', Auth::user()->id)->value('city');
        } else {
            $legalAddressCity = '';
            $addressCity = '';
        }

        Gate::authorize("merchant-edit", $record);
        $form = new ExtendMerchantSelfForm($record);
        $trAreas = TRLocation::query()->select('name', 'id')->orderBy('name')->get();

        $form = $form->formRenderEdit();
        return view("merchant.self", compact("record", 'equal',"area","form", "countries", "cities", "regions", "areas", "postCodes", "trAreas", "legalAddressCity", "addressCity"));
    }

    public function actionSelfPost(MerchantSelfPostRequest $request)
    {

        $this->catalogMerchant->delete();
        if (isset($request->sale_category)){
            foreach ($request->sale_category as $item){
                $this->catalogMerchant->create($item);
            }
        }
        $this->company->update($request->all(),Auth::user()->id);
        $record = Merchant::query()->findOrFail(Auth::user()->id);
        Gate::authorize("merchant-edit", $record);
        $record->status = 1;
        $this->user->updateMerchant($request->all(),Auth::user()->id);
        //$form = new MerchantSelfForm($record);
        //$form->formSave(request()->all());
        $body = view("mail.merchant_verification", ["id" => Auth::user()->id])->render();
        //SendMailService::sendNotification("Проверка мерча", $body);
        return ["redirect" => route("merchant.self", ["send" => true])];
    }

    public function actionDel($id)
    {
        $record = Merchant::query()->findOrFail($id);
        Gate::authorize("merchant-del", $record);
        $record->delete();
        return redirect(route("merchant.list"));
    }



    public function getOrder(Request $request){
            Gate::authorize("show-merchant");

            $orders = Order::query()
                ->when(Auth::user()->role == 'merchant',function ($q){
                    $q->whereHas('items.item.merchant',function ($query){
                        $query->where('user_id','=',Auth::user()->id);
                    });
                })
                ->when(empty($request->to),function ($q){
                    $q->whereBetween('created_at',[Carbon::now()->subDays(7)->format('Y-m-d 00:00"00'),Carbon::now()->format('Y-m-d 23:59:59')]);
                })
                ->when(!empty($request->to),function ($q) use ($request){
                    $q->whereBetween('created_at',[Carbon::parse($request->from)->format('Y-m-d 00:00"00'),Carbon::parse($request->to)->format('Y-m-d 23:59:59')]);
                })
                ->when(!empty($request->orders_status) &&  ($request->orders_status != 'all' ),function ($q) use ($request){
                        $q->where('status','=',$request->orders_status);
                })
                ->when(!empty($request->merchant) &&  ($request->merchant != 'all' ),function ($q) use ($request){
                    $q->whereHas('items.item.merchant',function ($query) use ($request){
                        $query->where('user_id','=',$request->merchant);
                    });
                })
                ->with(['commission',
                        'items'
                    ])
                ->orderByDesc('id')
                ->get();

            $total_price = 0;
            $total_commission = 0;
            $sum_delivery_price = 0;
            $total_price_order = 0;
            $total_sale_order = 0;
            foreach ($orders as $order){

                $order->delivery_sum = $order->delivery_price + $order->tr_delivery_price;

                $order->delivery_dt_end = Carbon::parse($order->created_at)->addDays(15)->format('Y-m-d');
                $order->left = Carbon::parse($order->created_at)->addDays(15)->diffInDays(Carbon::now());
                $order->left = $order->left < 0 ? 0 : $order->left;
                $order->left = $this->num_decline( $order->left, [trans('system.day'), trans('system.day1'), trans('system.day2')] );
                $order->order_price = $order->delivery_price + $order->price;



                $total_price_order += $order->order_price;
                $total_price += $order->commission[0]['total_price'] ?? 0;
                $total_commission += $order->commission[0]['commission_price'] ?? 0;
                $sum_delivery_price += $order->delivery_sum;
                $total_sale_order += $order->sale;
            }

            $orders->total_price_order = $total_price_order;
            $orders->total_price = $total_price;
            $orders->total_commission = $total_commission;
            $orders->total_price_without_commission = $orders->sum('price');
            $orders->sum_delivery_price = $sum_delivery_price;
            $orders->total_sale_order = $total_sale_order;

            $data = $orders;

            $merchants = User::where('role','=','merchant')
                ->get();

            return view("merchant.orders", compact( "data",'merchants'));
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

    public function getPays(){
        return view("merchant.pays");
    }

    public function exportOrders(Request $request){
        $response =  Excel::download(new OrderExport($request->from,$request->to,$request->merchant ?? null, $request->orders_status ?? null), 'Items.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }


    public function cancelOrder(Order $id){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $rates = CurrencyRate::where('id','=',2)
            ->first();

        $price = intval($id->price / $rates->rate_end) + ceil(($id->delivery_price + $id->tr_delivery_price) / $rates->rate_end) ;

        $request = new \Iyzipay\Request\CreateRefundRequest();
        $request->setLocale(\Iyzipay\Model\Locale::EN);
        $request->setConversationId(rand(0,9999999999));
        $request->setPaymentTransactionId($id->payment_id);
        $request->setPrice(intval($price));
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setIp($ip);

        $options = new \Iyzipay\Options();
        $options->setApiKey("bndv9YASgfDvKS6ZWzPiq3J4Ow3wU4q2");
        $options->setSecretKey("ixAzd6UNXi1vhRpVZ2tUe5kcAO6Pl4Fd");
        $options->setBaseUrl("https://api.iyzipay.com");
        $request->setLocale(\Iyzipay\Model\Locale::EN);

        $refundToBalance = \Iyzipay\Model\Refund::create($request, $options);
        log::info(print_r($refundToBalance,true));
        $orderItem = OrderItem::where('order_id','=',$id->id)
            ->first();

        $size = CatalogCharacteristicItem::where('catalog_characteristic_id','=',16)
            ->where('name_tr','=',$orderItem->size)
            ->first();

        $item = ProductItem::where('item_id','=',$orderItem->catalog_item_id)
                ->where('color','=',$orderItem->color)
                ->where('size','=',$size->id)
                ->first();

        $item->count = $item->count + $orderItem->count;
        $item->save();

        $id->status = 7;
        $id->save();
        return Redirect::to(route("merchant.orders"));
    }

    public function getOrderPage($id){
        $record = Customer::query()->findOrFail(Auth::user()->id);
        $order = OrderInfoResource::collection(Order::query()->where(['id' => $id])->get())->resolve();
        $items = OrderItemResource::collection(OrderItem::query()->where('order_id', $id)->get())->resolve();
        //Gate::authorize("customer-orders", $record);

        return view("merchant.order", compact("record", "order", "items"));
    }

    public function setStatusOnWay(Order $id)
    {
        $id->status = 2;
        $id->save();
        return Redirect::to(route("merchant.orders"));
    }


}
