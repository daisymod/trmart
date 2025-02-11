<?php

namespace App\Clients;

use App\Enums\KazPost\Enums;
use App\Models\City;
use Exception;
use Illuminate\Support\Facades\Log;
use SoapClient;
use Throwable;

class KazPost
{
    public function getAddrLetter($order): string|object
    {
        try {
            $opts = array(
                'https' => array(
                    'user_agent' => 'PHPSoapClient'
                )
            );
            $context = stream_context_create($opts);

            $wsdlUrl = 'https://rates.kazpost.kz/postratesprod/postratesws.wsdl';
            $soapClientOptions = array(
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE
            );

            $client = new SoapClient($wsdlUrl, $soapClientOptions);

            $body = [
                'key' => '70981f7073af4bd6815b21477222f5ff',
                'AddrInfo' => [
                    'RcpnName'          => $order->surname.' '.$order->name ,
                    'RcpnPhone'         => $order->phone,
                    'RcpnEmail'         => $order->email,
                    'RcpnCountry'       => $order->country_name,
                    'RcpnIndex'         => $order->postcode,
                    'RcpnCity'          => $order->city_name,
                    'RcpnStreet'        => $order->street,
                    'RcpnHouse'         => $order->house_number.', '.$order->room,
                    'SndrBIN'           => Enums::SndrBIN,
                    'SndrName'          => Enums::SndrName,
                    'SndrPhone'         => Enums::SndrPhone,
                    'SndrEmail'         => Enums::SndrEmail,
                    'SndrCountry'       => Enums::SndrCountry,
                    'SndrIndex'         => Enums::SndrIndex,
                    'SndrCity'          => Enums::SndrCity,
                    'SndrDistrict'      => Enums::SndrDistrict,
                    'SndrStreet'        => Enums::SndrStreet,
                    'Weight'            => $order->real_weight / 1000,
                    'DeclaredValue'     => $order->price,
                    'DeliverySum'       => $order->delivery_kz_weighing, //'CashOnDelivery' => $order->delivery_price,
                    'ProductCode'       => 'P103',
                    'Marks' => [
                        'Mark'          => 'returnAfter'
                    ],
                    'SendMethod'        => '1',
                    'MailCtg'           => '2',
                    'OrderNum'          => $order->id,
                    'MailCount'         => '1'
                ]
            ];

            return $client->GetAddrLetter($body);
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function searchCity($city): string|object
    {
        try {
            $opts = array(
                'https' => array(
                    'user_agent' => 'PHPSoapClient'
                )
            );
            $context = stream_context_create($opts);

            $wsdlUrl = 'https://rates.kazpost.kz/postratesprod/postratesws.wsdl';
            $soapClientOptions = array(
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE
            );

            $client = new SoapClient($wsdlUrl, $soapClientOptions);

            $body = [
                'key' => '70981f7073af4bd6815b21477222f5ff',
                'depname' => $city
            ];

            return $client->searchCity($body);
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getPostRate($weight, $price, $postcode)
    {
        Log::info(print_r($weight,true));
        Log::info(print_r($price,true));
        Log::info(print_r($postcode,true));
        try {
            $client = new SoapClient('https://rates.kazpost.kz/postratesprod/postratesws.wsdl');

            $body = [
                'key' => '70981f7073af4bd6815b21477222f5ff',
                'GetPostRateInfo' => [
                    'SndrCtg' => '2',
                    'Product' => 'P103',
                    'MailCat' => '4',
                    'SendMethod' => '1',
                    'Weight' => $weight,
                    'Value' => $price,
                    'From' => Enums::SndrIndex,
                    'To' => $postcode
                ]
            ];
            Log::info(print_r($client->GetPostRate($body),true));
            return $client->GetPostRate($body);
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function GetAddrLetterUsingBarcodeRequest($barcode)
    {
        try {
            $client = new SoapClient('http://rates.kazpost.kz/postrateprodv2/postratesws.wsdl');

            $body = [
                'key' => '70981f7073af4bd6815b21477222f5ff',
                'barcode' => $barcode
            ];

            return $client->GetAddrLetterUsingBarcode($body);
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
