<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use function GuzzleHttp\Promise\all;


class OrderExport implements FromArray,WithColumnWidths,ShouldQueue
{

    public function __construct(protected $from,protected $to,protected $merchant,protected $status)
    {
    }

    public function array(): array
    {

        $orders = Order::query()
            ->when(Auth::user()->role == 'merchant',function ($q){
                $q->whereHas('items.item.merchant',function ($query){
                    $query->where('user_id','=',Auth::user()->id);
                });
            })
            ->when(Auth::user()->role == 'admin',function ($q){
                $q->when($this->merchant != 'all' ,function ($query){
                    $query->whereHas('items.item.merchant',function ($query){
                        $query->where('user_id','=',$this->merchant);
                    });
                });
            })
            ->when($this->status != 'all' && $this->status != null,function ($q){
                $q->where('status','=',$this->status);
            })
            ->whereBetween('created_at',[Carbon::parse($this->from)->format('Y-m-d 00:00:00'),Carbon::parse($this->to)->format('Y-m-d 23:59:59')])
            ->with(['commission',
                'items',
                'items.item',
                'user',
                'items.item.merchant'
            ])
            ->orderByDesc('id')
            ->get();


        $total_price = 0;
        $total_commission = 0;
        $total_delivery_kz = 0;
        $total_delivery_tr = 0;
        $total_delivery_kz_w = 0;
        $total_delivery_tr_w = 0;
        $total_sale_order = 0 ;

        foreach ($orders as $order){
            $order->delivery_sum = $order->delivery_price;

            $order->delivery_dt_end = Carbon::parse($order->created_at)->addDays(15)->format('Y-m-d');
            $order->left = Carbon::parse($order->created_at)->addDays(15)->diffInDays(Carbon::now());
            $order->left = $order->left < 0 ? 0 : $order->left;
            $order->left = $this->num_decline( $order->left, [trans('system.day'), trans('system.day1'), trans('system.day2')] );


            $total_price += $order->price ?? 0;
            $total_commission += $order->commission[0]['commission_price'] ?? 0;
            $total_delivery_kz += $order->delivery_price ?? 0;
            $total_delivery_tr += $order->tr_delivery_price ?? 0;
            $total_delivery_kz_w += $order->delivery_kz_weighing ?? 0;
            $total_delivery_tr_w += $order->delivery_tr_weighing ?? 0;
            $total_sale_order += $order->sale ?? 0;
        }

        $orders->total_price = $total_price;

        $orders->total_commission = $total_commission;

        $orders->total_delivery_kz = $total_delivery_kz;
        $orders->total_delivery_tr = $total_delivery_tr;
        $orders->total_delivery_kz_w = $total_delivery_kz_w;
        $orders->total_delivery_tr_w = $total_delivery_tr_w;
        $orders->total_sale_order = $total_sale_order;

        $orders->total_price_without_commission = $orders->sum('price');

        $data = $orders;

            if (Auth::user()->role == 'merchant'){
                $array = [
                    ['ФИО покупателя','дата покупки','адрес доставки','наименование товара','артикул товара','цена одной позиции в лирах','цена заказа','комиссия','Статус Заказа']
                ];
                $itemArray = array();

                foreach ($orders as $item){
                    switch ($item->status){
                        case 1:
                            $status = trans('customer.orders.status.status_1');
                            break;
                        case 2:
                            $status = trans('customer.orders.status.status_2');
                            break;
                        case 3:
                            $status = trans('customer.orders.status.status_3');
                            break;
                        case 4:
                            $status = trans('customer.orders.status.status_4');
                            break;
                        case 5:
                            $status = trans('customer.orders.status.status_5');
                            break;
                        case 6:
                            $status = trans('customer.orders.status.status_6');
                            break;
                        case 7:
                            $status = trans('customer.orders.status.status_0');
                            break;
                    }


                    array_push($itemArray,[
                        $item->name . ' '. $item->surname ,
                        Carbon::parse($item->created_at)->format('Y-m-d'),
                        $item->country_name .' г. ' . $item->city_name . ' ул. '. $item->street . ' д. '. $item->house_number  . ' кв. '. $item->house_number,
                        $item->items[0]->item->name_ru,
                        $item->items[0]->item->article,
                        $item->items[0]->item->price,
                        $item->price,
                        $item->commission[0]->commission_price,
                        $status
                    ]);
                }


                $arrayTotal = [
                    ['общая сумма','общая сумма - минус комиссия']
                ];

                return [
                    $array,
                    $itemArray,
                    $arrayTotal,
                    [$orders->total_price_without_commission,$orders->total_price - $orders->total_commission]
                ];
            }else{
                $array = [
                    ['Статус','Мерчант','дата покупки','бренд','наименование товара','артикул товара','цена всех позиции в тенге','комиссия в тенге', 'Сумма заказа с доставкой в тенге','Сумма доставки тенге (Казпочта)','Сумма доставки тенге (Turkey)','Скидка','"доставка КЗ для юзера" (казпочта)','"доставка Турция для юзера" (из Турции в Казахстан)']
                ];
                $itemArray = array();

                foreach ($orders as $item){
                    switch ($item->status){
                        case 1:
                            $status = trans('customer.orders.status.status_1');
                            break;
                        case 2:
                            $status = trans('customer.orders.status.status_2');
                            break;
                        case 3:
                            $status = trans('customer.orders.status.status_3');
                            break;
                        case 4:
                            $status = trans('customer.orders.status.status_4');
                            break;
                        case 5:
                            $status = trans('customer.orders.status.status_5');
                            break;
                        case 6:
                            $status = trans('customer.orders.status.status_6');
                            break;
                        case 7:
                            $status = trans('customer.orders.status.status_0');
                            break;
                    }


                    array_push($itemArray,[
                        $status,
                        $item->items[0]->item->merchant->s_name ?? ' ' . ' '. $item->items[0]->item->merchant->name ?? ' ',
                        Carbon::parse($item->created_at)->format('Y-m-d'),
                        $item->items[0]->item->brand,
                        $item->items[0]->item->name_ru,
                        $item->items[0]->item->article,
                        $item->price,
                        $item->commission[0]->commission_price,
                        $item->price + $item->delivery_price + $item->tr_delivery_price ,
                        $item->delivery_price,
                        $item->tr_delivery_price,
                        $item->sale,
                        $item->delivery_kz_weighing,
                        $item->delivery_tr_weighing,
                    ]);
                }

                $arrayTotal = [
                    ['общая сумма за все заказы',
                        'общая сумма комиссии за все заказы',
                        'общая сумма  доставки за все заказы в тенге',
                        'общая сумма доставки за все заказы в тенге (из Турции в Казахстан)',
                        'общая сумма "доставка КЗ для юзера" (казпочта)',
                        'общая сумма "доставка Турция для юзера" (из Турции в Казахстан)',
                        'Сумма заказа с доставкой',
                        'общая сумма Скидки']
                ];

                return [
                    $array,
                    $itemArray,
                    ['','',''],
                    ['','',''],
                    ['','',''],
                    $arrayTotal,
                    [$orders->total_price,
                        $orders->total_commission,
                        $orders->total_delivery_kz_w,
                        $orders->total_delivery_tr_w,
                        $orders->total_delivery_kz,
                        $orders->total_delivery_tr,

                        ($orders->total_delivery_kz + $orders->total_delivery_tr + $orders->total_price )

                        ,$orders->total_sale_order]
                ];
            }


    }



    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 75,
            'D' => 25,
            'E' => 25,
            'F' => 25,
            'G' => 25,
            'H' => 25,
            'I' => 25,
            'J' => 25,
            'K' => 25,
        ];
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

}
