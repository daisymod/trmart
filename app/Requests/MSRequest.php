<?php


namespace App\Requests;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;

class MSRequest    extends BaseRequest
{
    public function getData($category,$page){

        $page == 0 ?  $currentpage = "" : $currentpage = "&currentPage=".$page;
        $this->request = new Request('GET', "https://api.ozdilekteyim.com/rest/v2/magaza-magaza-store/products/search?fields=products(code%2Cname%2Csummary%2CcustomUrl%2Cprice(FULL)%2Cbrand%2Ccategories(name)%2ClistPrice%2Cimages(DEFAULT)%2Cstock(FULL)%2CaverageRating%2CvariantOptions%2CvariantCount%2CdiscountRate%2Cbadges%2CnumberOfReviews%2CminOrderQuantity%2CorderQuantityInterval%2CmaxOrderQuantity%2CdefaultPickerValue%2Cunit)%2Cfacets%2Cbreadcrumbs%2Cpagination(DEFAULT)%2Csorts(DEFAULT)%2CfreeTextSearch%2CcurrentQuery&query=%3Arelevance%3AallCategories%3A".$category."&pageSize=36&lang=tr&curr=TRY".$currentpage, ['Content-Type' => 'application/json']);

        try {
            // отправка запроса и получение результата
            $response = $this->client->send($this->request,[
                'verify' => false,
                'connect_timeout' => 60

            ]);

            return ['data' => json_decode($response->getBody()->getContents(), true), 'code' => 200];

        }
        catch (ServerException | ClientException $exception)
        {
            if ($exception->getCode() === 500){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 500];
            }
            if ($exception->getCode() === 400){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 400];
            }
            if ($exception->getCode() === 404){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 404];
            }
            if ($exception->getCode() === 422){
                return ['data' => ['error' => 'Такого записи нет'], 'code' => 422];
            }
            return ['data' => ['error' => $exception->getMessage()], 'code' => $exception->getCode()];
        }

    }



    public function getDataProduct($url){
        $this->request = new Request('GET', "https://api.ozdilekteyim.com/rest/v2/magaza-magaza-store/products/".$url.'?fields=name,description,discountRate,images,customUrl,brand,purchasable,baseOptions(DEFAULT),baseProduct,variantOptions(FULL),variantType&lang=tr&curr=TRY', ['Content-Type' => 'application/json']);

        try {
            // отправка запроса и получение результата
            $response = $this->client->send($this->request,[
                'verify' => false,
                'connect_timeout' => 60

            ]);

            return ['data' => json_decode($response->getBody()->getContents(), true), 'code' => 200];

        }
        catch (ServerException | ClientException $exception)
        {
            if ($exception->getCode() === 500){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 500];
            }
            if ($exception->getCode() === 400){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 400];
            }
            if ($exception->getCode() === 404){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 404];
            }
            if ($exception->getCode() === 422){
                return ['data' => ['error' => 'Такого записи нет'], 'code' => 422];
            }
            return ['data' => ['error' => $exception->getMessage()], 'code' => $exception->getCode()];
        }
    }
}
