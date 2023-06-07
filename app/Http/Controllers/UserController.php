<?php

namespace App\Http\Controllers;

use App\Forms\CustomerEditForm;
use App\Forms\CustomerSelfForm;
use App\Forms\UserAdminForm;
use App\Http\Requests\MerchantEditPostRequest;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UserEditPostRequest;
use App\Http\Requests\UserLoginPostRequest;
use App\Http\Requests\UserRegFromPostRequest;
use App\Http\Requests\UserRegSMSPostRequest;
use App\Models\CatalogItem;
use App\Models\City;
use App\Models\Code;
use App\Models\Country;
use App\Models\KPLocation;
use App\Models\KPPostCode;
use App\Models\Merchant;
use App\Models\News;
use App\Models\User;
use App\Services\AdminStatisticService;
use App\Services\CodeService;
use App\Services\MerchantService;
use App\Services\UserLoginService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController
{

    public function __construct(protected MerchantService $service,protected AdminStatisticService $statistic)
    {
    }

    public function actionLK()
    {
        $role = Auth::user()->role;

        $statisticUserAll =  number_format($this->service->getStatisticByPeriodUser(null), 2, '.', ' ');
        $statisticUserMonth = number_format($this->service->getStatisticByPeriodUser(true), 2, '.', ' ');
        $statisticUserCount = $this->service->getBuyOrder();

        $statisticAll = $this->service->getStatisticByPeriod(null);
        $statisticMonth = $this->service->getStatisticByPeriod(true);
        $countProduct = $this->service->getStatisticByPeriod(null);

        $totalMerchant = $this->statistic->getCountMerchant(null,0);
        $totalMerchantMonth = $this->statistic->getCountMerchant(true,30);
        $totalMerchantTwoMonth = $this->statistic->getCountMerchant(true,60);
        $diffPercentMerchant = $totalMerchantTwoMonth != 0 ? ($totalMerchantMonth / $totalMerchantTwoMonth) : 100;

        $totalUser = $this->statistic->getCountUser(null,0);
        $totalUserMonth = $this->statistic->getCountUser(true,30);
        $totalUserTwoMonth = $this->statistic->getCountUser(true,60);
        $diffPercentUser = $totalUserTwoMonth != 0 ? ($totalUserMonth / $totalUserTwoMonth) :  100 ;


        $totalPrice = $this->statistic->getPriceCommission(null,0);
        $totalPriceMonth = $this->statistic->getPriceCommission(true,30);
        $totalPriceTwoMonth = $this->statistic->getPriceCommission(true,60);
        $diffPercentPrice = $totalPriceTwoMonth !=0 ? $totalPriceMonth / $totalPriceTwoMonth : 100;

        return view("user.lk.{$role}",
            compact(
                'statisticAll',
                'statisticMonth',
                'countProduct',
                'totalMerchant',
                'totalMerchantMonth',
                'totalMerchantTwoMonth',
                'diffPercentMerchant',

                'statisticUserAll',
                'statisticUserMonth',
                'statisticUserCount',

                'totalUser',
                'totalUserMonth',
                'totalUserTwoMonth',
                'diffPercentUser',

                'totalPrice',
                'totalPriceMonth',
                'totalPriceTwoMonth',
                'diffPercentPrice',
            ));
    }

    public function actionLoginPost(UserLoginPostRequest $request)
    {
        Auth::login(User::getOnPhone($request->get("phone")));
        return ["redirect" => route("user.lk")];
    }

    public function actionRegPost(UserRegFromPostRequest $request)
    {
        UserLoginService::putRegData($request->all());
        return $request->all();
    }

    public function actionExit()
    {
        Auth::logout();
        return redirect("/");
    }

    public function actionRegSMSPost(UserLoginService $userLoginService, UserRegSMSPostRequest $request)
    {
        $userLoginService->regUser(UserLoginService::getRegData());

        return ["redirect" => Auth::user()->role == 'user' ? route("user.lk") : route("index")];
    }



    public function resetPasswordCode(PhoneRequest $request)
    {
        UserLoginService::resetPasswordCode($request->all());
        return $request->all();
    }

    public function checkPasswordCode(Request $request){
        $codeModel =new Code();
        $code = new CodeService($codeModel);
        $checkTimeSms = $code->getByIdSms(request()->sms);


        $time = strtotime(Carbon::now()) - strtotime($checkTimeSms->created_at);
        if (!empty($checkTimeSms->phone)){    if ($time > 180){
            $code->delete(request()->phone);
            UserLoginService::resetPasswordCode($request->all());
            throw ValidationException::withMessages([trans('system.codeError')]);
        }else{
            $phone = $checkTimeSms->phone;
            return response()->json(['phone' => $phone]);
        }

        }else{
            throw new \Exception(trans('system.codeNotFound'),422);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        User::where('phone','=',$request->phone)
            ->update([
                'password' => Hash::make($request->password),
            ]);

        $user = User::where('phone','=',$request->phone)
            ->first();

        Auth::login($user);

        return ["redirect" => route("user.lk")];
    }


    public function actionList(Request $request)
    {
        Gate::authorize("user-list");
        $records = User::query()
            ->when(empty($request->role),function ($q)  {
                $q->where("role", "user");
                $q->orWhere("role", "logist");
            })
            ->when(!empty($request->s_name),function ($q) use ($request){
                $q->where('s_name','LIKE','%'.$request->s_name.'%');
            })
            ->when(!empty($request->name),function ($q) use ($request){
                $q->where('name','LIKE','%'.$request->name.'%');
            })
            ->when(!empty($request->m_name),function ($q) use ($request){
                $q->where('m_name','LIKE','%'.$request->m_name.'%');
            })
            ->when(!empty($request->phone),function ($q) use ($request){
                $q->where('phone','LIKE','%'.$request->phone.'%');
            })
            ->when(!empty($request->role),function ($q) use ($request){
                $q->where('role','=',$request->role);
            })
            ->paginate(50);

        $model = new User();
        $model->s_name = $request->s_name;
        $model->name = $request->name;
        $model->role = $request->role ?? 'user';
        $model->m_name = $request->m_name;
        $model->phone = $request->phone;

        $form = new UserAdminForm($model);
        $form = $form->formRenderEdit();

        return view("user.list", compact("records",'form'));
    }

    public function actionEditGet($id)
    {
        $record = User::query()->findOrFail($id);
        Gate::authorize("user-edit", $record);
        $form = new CustomerEditForm($record);
        $form = $form->formRenderEdit();
        $countries = Country::query()
            ->select('id', 'name_ru')
            ->get()
            ->values();;

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

        return view("user.edit", compact("record", "form", "countries", "cities", "regions", "areas", "postCodes"));
    }

    public function actionEditPost($id, UserEditPostRequest $request)
    {
        $record = User::query()->findOrFail($id);
        Gate::authorize("user-edit", $record);
        $form = new CustomerEditForm($record);
        $form->formSave(request()->all());
        return ["redirect" => route("user.list")];
    }

    public function actionDel($id)
    {
        $record = User::query()->findOrFail($id);
        Gate::authorize("user-del", $record);
        $record->delete();
        return redirect(route("user.list"));
    }
}
