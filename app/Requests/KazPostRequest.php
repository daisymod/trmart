<?php


namespace App\Requests;

use App\Enums\KazPost\KazPostStatus;
use App\Enums\KazPost\KazPostTypePo;
use App\Models\Company;
use App\Models\User;
use Exception;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Throwable;

class KazPostRequest extends BaseRequest
{
    public const DELIVERY_ID = 3;



    public function checkStatus($order)
    {
        //print_r("\n"."https://track.post.kz/api/v2/$order->barcode/events"."\n");
        $this->request = new Request(
            'GET',
            'https://track.post.kz/api/v2/'.$order->barcode.'/events',
            [ 'Content-Type' => 'application/json', ]
        );

        // отправка запроса и получение результата
        $response = $this->client->send(
            $this->request,
            [
            ]
        );

        return json_decode($response->getBody()->getContents());
    }


    public function tracking($order)
    {
        try
        {
            DB::beginTransaction();
           // print_r($order->barcode);
            $serverResponse = $this->checkStatus($order);
            $this->updateStatus($order, $serverResponse->events);
            $serverResponse->events;
            DB::commit();
        } catch(Throwable $e)
        {
            DB::rollBack();
            report($e);
        }
    }

    public function updateStatus($order, $events)
    {

        switch ($order->status){
            case 4:
                $this->storeOnWayStatus($order, $events);
                break;
            case 5:
                $this->storeDeliveredStatus($order, $events);
                break;
        }
    }

    public function storeOnWayStatus($order, $events)
    {
        $statuses = KazPostStatus::ON_WAY;
        foreach ($statuses as $status)
        {
            foreach ($events[0]->activity as $event)
            {
                if ($status == $event->status[0])
                {
                    $order->update([
                        'status' => 5
                    ]);
                    break 2;
                }
            }
        }
    }

    public function storeOnExpectedStatus($order, $events)
    {
        $statusesOnExpected = KazPostStatus::ON_EXPECTED;
        $statusesReturn = KazPostStatus::RETURN;
        foreach ($events[0]->activity as $event)
        {
            // Ожидает на ПВЗ
            foreach ($statusesOnExpected as $status)
            {
                if ($status == $event->status[0])
                {
                    $order->update([
                        'status' => 6
                    ]);
                    break 2;
                }
            }
            // Возврат

        }
    }

    public function storeDeliveredStatus($order, $events)
    {
        $statuses = KazPostStatus::DELIVERED;
        foreach ($statuses as $status)
        {
            foreach ($events[0]->activity as $event)
            {
                if ($status == $event->status[0])
                {

                    $order->update([
                        'status' => 6
                    ]);
                    break 2;
                }
            }
        }
    }

    public function getActiveStorageFaculty($cityName, $region)
    {
        $cities = new \SoapClient('http://rates.kazpost.kz/postratesprod/postratesws.wsdl');

        $toIndex = $cities->searchCity(
            [
                "depname" => $cityName,
                "oblast" => "",
                "rayon" => ""
            ]
        )
            ->deps;

        $toIndex = $toIndex->depList;

        $toPstIndex = null;
        $storageFacilities = [];
        foreach($toIndex as $kazpostPvz)
        {
            if( !empty ($kazpostPvz->PstIndex) && !empty ($kazpostPvz->Address))
            {
                if( explode(",", $kazpostPvz->Address)[0] == $region.' область'
                    || $cityName == 'Алматы'
                    || $cityName == 'Астана'
                    || $cityName == 'Шымкент')
                {
                    $toPstIndex = $kazpostPvz->PstIndex;

                    if( isset($kazpostPvz->Address)
                        &&  isset($kazpostPvz->GeoMaps)
                        && isset($kazpostPvz->Schedule)
                        && isset($kazpostPvz->TypePo)
                        && isset($kazpostPvz->Filial))
                    {
                        foreach (KazPostTypePo::TYPE_PO_ARRAY as $item)
                        {
                            if( $kazpostPvz->TypePo == $item)
                            {
                                $responseData = [
                                    'code' => $kazpostPvz->PstIndex,
                                    'remote_city_id' => $kazpostPvz->PstIndex,
                                    'service_delivery_id' => KazPostRequest::DELIVERY_ID,
                                    'name' => $kazpostPvz->Filial,
                                    'address' => $kazpostPvz->Address,
                                    'latitude' => isset($kazpostPvz->GeoMaps) ? explode(',', $kazpostPvz->GeoMaps)[0] : null,
                                    'longitude' => isset($kazpostPvz->GeoMaps) ? explode(',', $kazpostPvz->GeoMaps)[1] : null,
                                    'code' => isset($kazpostPvz->Code) ? $kazpostPvz->Code : null,
                                    'max_weight' => 100,
                                    'is_self_pickup'      => 1,
                                    'is_self_delivery'    => 1,
                                    'is_self_payment'     => 1,
                                ];

                                $operations = [];
                                // if ($touch->is_handout) {
                                $operations[] = 'SelfDelivery';
                                // }
                                // if ($touch->is_reception) {
                                $operations[] = 'SelfPickup';
                                // }
                                // if ($touch->have_cashless) {
                                $operations[] = 'PaymentByBankCard';
                                // }
                                // if ($touch->have_cash) {
                                $operations[] = 'Payment';
                                // }

                                $scheduleData = [];
                                foreach ($operations as $operation) {

                                    $scheduleData[] = [
                                        'work_time' => $kazpostPvz->Schedule,
                                        'operation_type' => $operation,
                                    ];
                                }
                                array_push($responseData, $scheduleData);
                                array_push($storageFacilities, $responseData);
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $storageFacilities;
    }

}
