<?php

namespace App\Http\Controllers;

//Illuminate
use App\Mail\NewBarcodeKazPost;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
//App
use App\Http\Resources\LogistOrdersResource;
use App\Exports\LogistFormExport;
use App\Clients\KazPost;
use App\Models\Customer;
use App\Models\KPForm;
use App\Models\Order;
//Maatwebsite
use Maatwebsite\Excel\Facades\Excel;
//Symfony
use Symfony\Component\HttpFoundation\BinaryFileResponse;
// Carbon
use Carbon\Carbon;


class LogistController extends Controller
{
    private function exportExcel($form, $orders): BinaryFileResponse
    {
        $fileName = 'Turkiyemart-'.$form->id.'-'.Carbon::parse($form->created_at)->format('d.m.Y').'-f103.xlsx';
        return Excel::download(new LogistFormExport($orders), $fileName);
    }

    public function actionOrdersAutoGet(Request $request){
        Gate::authorize("logist-acceptance");
        $record = Customer::query()->findOrFail(Auth::user()->id);
        $start = $request->start ?? '';
        $end   = $request->end ?? '';

        if (count($request->all())) {
            $start = $request->start;
            $end   = $request->end;
            if (!$start || !$end) {
                return Redirect::back()->withErrors('Выберите дата начало и конца');
            } else {
                $orders = Order::query()
                    ->wherehas('items.item.catalog',function ($q){
                        $q->where('type_delivery','=',2);
                    })
                    ->whereDate('created_at','<=', Carbon::parse($end)->toDateString())
                    ->whereDate('created_at','>=', Carbon::parse($start)->toDateString())
                    ->when(!empty($request->id),function ($q) use ($request){
                        $q->where('id','=',$request->id);
                    })
                    ->when(!empty($request->barcode),function ($q) use ($request){
                        $q->where('barcode','=',$request->barcode);
                    })
                    ->when(!empty($request->orders_status) && $request->orders_status != 'all',function ($q) use ($request){
                        $q->where('status','=',$request->orders_status);
                    })
                    ->orderBy('id')
                    ->get();
            }
        } else {
            $orders = Order::query()
                ->wherehas('items.item.catalog',function ($q){
                    $q->where('type_delivery','=',2);
                })
                ->when(!empty($request->id),function ($q) use ($request){
                    $q->where('id','=',$request->id);
                })
                ->when(!empty($request->barcode),function ($q) use ($request){
                    $q->where('barcode','=',$request->barcode);
                })
                ->when(!empty($request->orders_status) && $request->orders_status != 'all',function ($q) use ($request){
                    $q->where('status','=',$request->orders_status);
                })
                ->orderBy('id')->get();
        }

        $data = LogistOrdersResource::collection($orders)->resolve();

        return view("logist.all_orders_auto", compact("record", "data", "start", "end"));
    }

    public function getOrders(Request $request){
        Gate::authorize("logist-acceptance");
        $record = Customer::query()->findOrFail(Auth::user()->id);
        $start = $request->start ?? '';
        $end   = $request->end ?? '';

        if (count($request->all())) {
            $start = $request->start;
            $end   = $request->end;
            if (!$start || !$end) {
                return Redirect::back()->withErrors('Выберите дата начало и конца');
            } else {
                $orders = Order::query()
                    ->whereDate('created_at','<=', Carbon::parse($end)->toDateString())
                    ->whereDate('created_at','>=', Carbon::parse($start)->toDateString())
                    ->when(!empty($request->id),function ($q) use ($request){
                        $q->where('id','=',$request->id);
                    })
                    ->wherehas('items.item.catalog',function ($q){
                        $q->where('type_delivery','=',1);
                    })
                    ->when(!empty($request->barcode),function ($q) use ($request){
                        $q->where('barcode','=',$request->barcode);
                    })
                    ->when(!empty($request->orders_status) && $request->orders_status != 'all',function ($q) use ($request){
                        $q->where('status','=',$request->orders_status);
                    })
                    ->orderBy('id')
                    ->get();
            }
        } else {
            $orders = Order::query()
                ->when(!empty($request->id),function ($q) use ($request){
                    $q->where('id','=',$request->id);
                })
                ->wherehas('items.item.catalog',function ($q){
                    $q->where('type_delivery','=',1);
                })
                ->when(!empty($request->barcode),function ($q) use ($request){
                    $q->where('barcode','=',$request->barcode);
                })
                ->when(!empty($request->orders_status) && $request->orders_status != 'all',function ($q) use ($request){
                    $q->where('status','=',$request->orders_status);
                })
                ->orderBy('id')->get();
        }

        $data = LogistOrdersResource::collection($orders)->resolve();

        return view("logist.all_orders", compact("record", "data", "start", "end"));
    }

    public function actionToCollect(Request $request): BinaryFileResponse|RedirectResponse
    {
        Gate::authorize("logist-orders");
        $orders = $request->input('orders');
        if (!$orders) {
            return Redirect::back()->withErrors('Выберите заказ');
        } else {
            $kpForm = new KPForm();
            $kpForm->orders = json_encode($orders);
            $kpForm->save();
            if ($kpForm->id) {
                foreach ($orders as $order) {
                    Order::query()
                        ->where('id', $order)
                        ->update(array('status' => 3));
                }
            } else {
                return Redirect::back()->withErrors('Ошибка при сохранение');
            }
            return \redirect('/logist/orders');
        }
    }

    public function actionToOrders(Request $request): BinaryFileResponse|RedirectResponse
    {
        Gate::authorize("logist-orders");
        $orders = $request->input('orders');
        if (!$orders) {
            return Redirect::back()->withErrors('Выберите заказ');
        } else {
            foreach ($orders as $order) {
                Order::query()
                    ->where('id', $order)
                    ->update(array('status' => 3));
            }

            return \redirect('/logist/acceptance');
        }
    }

    public function actionDownloadPdf($id): BinaryFileResponse
    {
        $client = new KazPost();
        $barcode = Order::query()->where('id', $id)->value('barcode');
        $base64Pdf = $client->GetAddrLetterUsingBarcodeRequest($barcode)->AddrLetPdf;
        $path       = public_path($barcode.'.pdf');
        $contents   = base64_decode($base64Pdf);
        file_put_contents($path, $contents);
        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function actionAddLetter($id): RedirectResponse
    {
        $order = Order::query()->find($id);
        if (isset($order)) {
            $client = new KazPost();
            $kpLetter = $client->getAddrLetter($order);
            if (isset($kpLetter->Barcodes)) {
                $order->barcode = $kpLetter->Barcodes;
                $order->save();

                $user = User::where('id','=',$order->user_id)
                    ->first();

                $user->barcode = $kpLetter->Barcodes;
                $user->order_id = $order->id;
                Mail::to($user->email)->send(new NewBarcodeKazPost($user));

                return \redirect()->back();
            } else {
                throw new \Exception($kpLetter->ResponseInfo->ResponseText);
            }
        } else {
            return Redirect::back()->withErrors('Заказ не найден!');
        }

    }

    public function actionAcceptanceGet(Request $request): Factory|View|RedirectResponse|Application
    {
        Gate::authorize("logist-acceptance");
        $record = Customer::query()->findOrFail(Auth::user()->id);
        $start = $request->start ?? '';
        $end   = $request->end ?? '';

        if (count($request->all())) {
            $start = $request->start;
            $end   = $request->end;
            if (!$start || !$end) {
                return Redirect::back()->withErrors('Выберите дата начало и конца');
            } else {
                $orders = Order::query()
                    ->where('status', 2)
                    ->whereDate('created_at','<=', Carbon::parse($end)->toDateString())
                    ->whereDate('created_at','>=', Carbon::parse($start)->toDateString())
                    ->orderBy('id')
                    ->get();
            }
        } else {
            $orders = Order::query()->where('status', 2)->orderBy('id')->get();
        }

        $data = LogistOrdersResource::collection($orders)->resolve();

        return view("logist.acceptance", compact("record", "data", "start", "end"));
    }

    public function actionOrdersGet(Request $request): Factory|View|RedirectResponse|Application
    {
        Gate::authorize("logist-orders");
        $record = Customer::query()->findOrFail(Auth::user()->id);
        $start = $request->start ?? '';
        $end   = $request->end ?? '';

        if (count($request->all())) {
            $start = $request->start;
            $end   = $request->end;
            if (!$start || !$end) {
                return Redirect::back()->withErrors('Выберите дата начало и конца');
            } else {
                $orders = Order::query()
                    ->where('status', 3)
                    ->whereDate('created_at','<=', Carbon::parse($end)->toDateString())
                    ->whereDate('created_at','>=', Carbon::parse($start)->toDateString())
                    ->orderBy('id')
                    ->get();
            }
        } else {
            $orders = Order::query()->where('status', 3)->orderBy('id')->get();
        }

        $data = LogistOrdersResource::collection($orders)->resolve();

        return view("logist.orders", compact("record", "data", "start", "end"));
    }

    public function actionCollectedGet(): Factory|View|Application
    {
        Gate::authorize("logist-collected");
        $data = KPForm::query()->where('status', 0)->orderByDesc('id')->get();

        return view("logist.collected", compact("data"));
    }

    public function actionCollectedItemGet($id): Factory|View|Application
    {
        Gate::authorize("logist-collected");
        $formId      = $id;
        $form        = KPForm::query()->where('id', $id)->get();
        $data        = Order::query()->whereIn('id', json_decode((string)$form[0]->orders))->get();
        $orders      = LogistOrdersResource::collection($data)->resolve();
        $addedOrders = Order::query()->where('status', 3)->pluck('id', 'id');

        return view("logist.item", compact("orders", "formId", "addedOrders"));
    }

    public function actionCollectedArchivalGet(): Factory|View|Application
    {
        Gate::authorize("logist-collected");
        $data = KPForm::query()->where('status', 1)->orderByDesc('id')->get();

        return view("logist.archival.collected", compact("data"));
    }

    public function actionCollectedArchivalItemGet($id): Factory|View|Application
    {
        Gate::authorize("logist-collected");
        $formId      = $id;
        $form        = KPForm::query()->where('id', $id)->get();
        $data        = Order::query()->whereIn('id', json_decode((string)$form[0]->orders))->get();
        $orders      = LogistOrdersResource::collection($data)->resolve();

        return view("logist.archival.item", compact("orders", "formId"));
    }

    public function actionCollectedItemExport($id): BinaryFileResponse
    {
        Gate::authorize("logist-orders");
        $form   = KPForm::find($id);
        $orders = json_decode((string)$form->orders);

        return $this->exportExcel($form, $orders);
    }

    public function actionCollectedItemDelete(Request $request): Redirector|Application|RedirectResponse
    {
        Gate::authorize("logist-orders");
        $formId = $request->id;
        $orderId = $request->form;
        $form = KPForm::query()->where('id', $formId);
        $arr = json_decode((string)$form->value('orders'));
        $arr = array_diff($arr, array($orderId));
        $form->update(['orders' => json_encode(array_values($arr))]);

        Order::query()
            ->where('id', $orderId)
            ->update(['status' => 3]);

        return \redirect('/logist/collected/item/'.$formId);
    }

    public function actionCollectedItemAdd(Request $request): Redirector|Application|RedirectResponse
    {
        Gate::authorize("logist-orders");
        $formId = $request->input('selected')[1];
        $orderId = $request->input('selected')[0];
        $form = KPForm::query()->where('id', $formId);
        $arr = json_decode((string)$form->value('orders'));
        $arr[] = strval($orderId);
        $form->update(['orders' => json_encode(array_values($arr))]);

        Order::query()
            ->where('id', $orderId)
            ->update(['status' => 4]);

        return \redirect('/logist/collected/item/'.$formId);
    }

    public function actionCollectedUpdateStatus($id): Redirector|Application|RedirectResponse
    {
        $orders = str_replace('"','',KPForm::query()->where('id', $id)->first()->orders);
        $orders = str_replace('[','',$orders);
        $orders = str_replace(']','',$orders);

        $order = Order::query()
            ->whereIn('id', explode(',',$orders))
            ->update(
                [
                    'status' => 4
                ]
            );

        Gate::authorize("logist-orders");
        KPForm::query()->where('id', $id)->update(['status' => 1]);


        return \redirect()->route('logist.collected');
    }


    public function changeStatus($id,Request $request){
        Order::query()
            ->where('id', '=',$id)
            ->update(
                [
                    'status' => $request->status
                ]
            );

        return \redirect()->route('logist.auto_orders');
    }

    public function download(Order $id){
        $weight = 0;
        foreach ($id->items as $item){
            $currentWeightItem = 0;
            $currentWeightItem = $item->item->weight / 1000 * $item->count;
            $weight +=$currentWeightItem;
        }
        $template = [
            'created_at' => Carbon::parse($id->created_at),
            'postcode' => $id->postcode,
            'phone' => $id->phone,
            'fio' => $id->name.' '.$id->surname.' '.$id->middle_name,
            'street' => $id->street,
            'city' => $id->city_name,
            'house_number' => $id->house_number,
            'room' => $id->room,
            'price' => $id->price,
            'delivery' => self::num2str($id->price),
            'to' => $id->merchantData->company->company_name ?? null,
            'weight' => $weight,
        ];
        $pdf = PDF::loadView('pdf.auto', compact('template'));
        $pdf->setPaper('a4' , 'landscape');
        return $pdf->download();
    }


    public static function num2str($num)
    {
        $nul = 'ноль';
        $ten = array(
            array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
            array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять')
        );
        $a20 = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать');
        $tens = array(2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
        $hundred = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
        $unit = array(
            array('копейка' , 'копейки',   'копеек',     1),
            array('теңге',    'теңге',     'теңге',     0),
            array('тысяча',   'тысячи',    'тысяч',      1),
            array('миллион',  'миллиона',  'миллионов',  0),
            array('миллиард', 'миллиарда', 'миллиардов', 0),
        );

        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub) > 0) {
            foreach (str_split($rub, 3) as $uk => $v) {
                if (!intval($v)) continue;
                $uk = sizeof($unit) - $uk - 1;
                $gender = $unit[$uk][3];
                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
                // mega-logic
                $out[] = $hundred[$i1]; // 1xx-9xx
                if ($i2 > 1) $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; // 20-99
                else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; // 10-19 | 1-9
                // units without rub & kop
                if ($uk > 1) $out[] = self::morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
            }
        } else {
            $out[] = $nul;
        }
        $out[] = self::morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
        $out[] = $kop . ' ' . self::morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
        return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
    }

    private static function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20) return $f5;
        $n = $n % 10;
        if ($n > 1 && $n < 5) return $f2;
        if ($n == 1) return $f1;
        return $f5;
    }
}
