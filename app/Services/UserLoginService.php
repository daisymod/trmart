<?php

namespace App\Services;

use App\Models\Code;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserLoginService
{
    public function regUser($data)
    {

        $codeModel =new Code();
        $code = new CodeService($codeModel);
        $checkTimeSms = $code->getById($data['phone'],request()->sms);


        $time = strtotime(Carbon::now()) - strtotime($checkTimeSms->created_at);
        if (!empty($checkTimeSms->phone)){

            if ($time > 180){
                $code->delete($data['phone']);
                static::putRegData($data);
                throw ValidationException::withMessages([trans('system.codeError')]);
            }else{

                $user = User::where('phone','=',$data["phone"])
                    ->first();

                if (empty($user->id)){
                    $user = new User($data);
                    $user->password = Hash::make($data["password"]);
                    $user->save();
                    Auth::login($user);
                    $code->delete($data['phone']);
                }
                return $user;
            }

        }else{
            throw new \Exception(trans('system.codeNotFound'),422);
        }

    }



    public function registerMerchant($data)
    {
        $data['name'] = $data['company'];
        $user = new User($data);
        $user->password = Hash::make($data["password"]);
        $user->name = $data['company'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->role = 'merchant';
        $user->active = 'Y';
        $user->status = 0;
        $user->save();
        Auth::login($user);
        return $user;
    }

    public static function putRegData($data)
    {
        session()->put("regData", $data);
        $regSMS = rand("111111", "999999");
        session()->put("regDataSMS", $regSMS);


        $data = [
            'phone' => $data["phone"],
            'code' => $regSMS,
            'session_id' => session()->get('_token')
        ];

        $codeModel =new Code();
        $code = new CodeService($codeModel);
        $code->create($data);

        $message = "Verification code - ". "turkiyemart.com:". $regSMS;

        SMSTrafficService::sendSMS(preg_replace("/[^0-9]/", "", $data["phone"]), $message);
    }




    public static function resetPasswordCode($data)
    {
        session()->put("regData", $data);
        $regSMS = rand("111111", "999999");
        session()->put("regDataSMS", $regSMS);


        $data = [
            'phone' => $data["phone"],
            'code' => $regSMS,
            'session_id' => session()->get('_token'),
            'is_reset_password' => 1
        ];

        $codeModel =new Code();
        $code = new CodeService($codeModel);
        $code->create($data);

        $message = "Sms for reset password - ". "turkiyemart.com:". $regSMS;

        SMSTrafficService::sendSMS(preg_replace("/[^0-9]/", "", $data["phone"]), $message);
    }

    public static function getRegData()
    {
        return session()->get("regData");
    }
}
