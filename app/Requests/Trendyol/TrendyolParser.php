<?php


namespace App\Requests\Trendyol;


use App\Requests\Trendyol\Response\ParsedProduct;
use App\Requests\Trendyol\Response\ParserResponse;
use App\Requests\Trendyol\Response\ProductVariant;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TrendyolParser
{
    /**
     * Url for parsing product from Trendyol
     *
     * @var string
     */
    private $url;

    /**
     * Guzzle Client for send request to url
     *
     * @var Client
     */
    private $client;

    /**
     * Trendyol grouped products response
     *
     * @var object
     */
    private $group;

    /**
     * Trendyol parsed single product response
     *
     * @var object
     */
    private $parsedProductResponse;

    /**
     * Self parsed product response
     *
     * @var ParsedProduct
     */
    private $parsedProduct;

    /**
     * Response object after parsing
     *
     * @var ParserResponse
     */
    public $response;

    /**
     * BaseUrl for images
     *
     * @var string
     */
    protected $imageBaseUrl = 'https://cdn.dsmcdn.com/mnresize/1872/1872';

    /**
     * Trendyol website base url
     *
     * @var string
     */
    private $baseUrl = 'https://www.trendyol.com';

    /**
     * GuzzleHttp Client config
     *
     * @var array
     */
    public $guzzle_config = [];

    /**
     * TrendyolParser constructor.
     */
    public function __construct()
    {
        $this->client = new Client($this->guzzle_config);
        $this->response = new ParserResponse();
    }

    /**
     * Parse from website. Init method
     *
     * @param $url
     * @return ParserResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function parse($url)
    {

        $this->url = $this->baseUrl.$url;
        $result =  $this->parseFromUrl();

        return $result;
    }

    /**
     * @param $url
     * @return ParserResponse
     */
    public function parseSingle($url): ParserResponse
    {
        $this->url = $url;
        $this->parseProductByUrl();
        if ($this->parsedProductResponse)
            $this->parseBaseItems()
                ->setProduct();
        return $this->response;
    }

    public function getImages($image)
    {
       $result = array();
       foreach ($image as $item){
           array_push($result,$this->imageBaseUrl.$item);
       }
       return $result;
    }

    public function getDescription($description)
    {
        $result = "";
        foreach ($description as $item){
            if (isset($item['viewType'])){
                if ($item['viewType'] == 'inline')
                    $result .= $item['text']."\n";
            }else{
                $result .= $item['text']."\n";
            }

        }
        return $result;
    }

    public function getCompound($compound)
    {
        $result = array();
        foreach ($compound as $item){
            if ($item['key']['name'] == 'Materyal'){
                array_push($result,$item['value']['name']);
                array_push($result,100);
            }
        }

        return $result;
    }

    public function getLength($product)
    {
        $result = 1;
        foreach ($product['descriptions'] as $item){
            if (strpos($item['text'], 'Ürün Ölçüleri Masa')){
                $data = explode(' ',$item['text']);
                $index = 0;
                foreach ($data as $word){
                    if (str_contains($word, 'uzunluk:')){
                        $length = explode(':',$word);
                        $result = intval($length[1]) ?? 1;
                    }
                }
            }
            else{
                $data = explode(' ',$item['text']);
                foreach ($data as $word){
                    if (preg_match('/^\d+x\d+/', $word)){
                        $length = explode('x',$word);
                        $result = intval($length[0]) ?? 1;
                    }
                }
            }
        }

        return $result;
    }

    public function getWidth($product)
    {
        $result = 1;
        foreach ($product['descriptions'] as $item){
            if (strpos($item['text'], 'Ürün Ölçüleri Masa')){
                $data = explode(' ',$item['text']);
                foreach ($data as $word){
                    if (str_contains($word, 'en:')){
                        $length = explode(':',$word);
                        $result = intval($length[1]) ?? 1;
                    }
                }
            }else{
                $data = explode(' ',$item['text']);
                foreach ($data as $word){
                    if (preg_match('/^\d+x\d+/', $word)){
                        $length = explode('x',$word);
                        $result = intval($length[1]) ?? 1;
                    }
                }
            }

        }

        return $result;
    }

    public function getHeight($product)
    {
        $result = 1;
        foreach ($product['descriptions'] as $item){
            if (strpos($item['text'], 'Ürün Ölçüleri Masa')){
                $data = explode(' ',$item['text']);
                $index = 0;
                foreach ($data as $word){
                    if (str_contains($word, 'h:')){
                        $length = explode(':',$word);
                        $result = intval($length[1]) ?? 1;
                    }
                    if (str_contains($word, 'yüksekliği')){
                        $result = intval($word[$index + 1]) ?? 1;
                    }
                    $index++;
                }
            }
        }

        return $result;
    }

    public function getSize($product)
    {
        $result = 'standart';
        foreach ($product as $item){
            if ($item['key']['name'] == 'Masa Ölçüsü'){
                $result = $item['value']['name'];
            }
        }

        return $result;
    }

    /**
     * Get product from url and parse it attributes
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function parseFromUrl()
    {
        return $this->parseProductByUrl();
    }

    public function getPageResponse($url)
    {
       return  $this->client->get($url)->getBody()->getContents();
    }

    /**
     * Get product data by url
     */
    private function parseProductByUrl()
    {
        $response = $this->client->get($this->url)->getBody()->getContents();
        $found = preg_match('/window\.__PRODUCT_DETAIL_APP_INITIAL_STATE__=(.+);/', $response);
        if ($found) {
            $text = substr($response, strpos($response, '__PRODUCT_DETAIL_APP_INITIAL_STATE__') + 37);
            $to = strpos($text, 'window.TYPageName') - 1;
            $json = substr($text, 0, $to);
            $this->parsedProductResponse = json_decode($json,true);
            return $this->parsedProductResponse;
        }

        return $this->parsedProductResponse;
    }

    /**
     * Get product group from api
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function parseProductGroup(): self
    {
        $productGroupId = $this->parsedProductResponse->product->productGroupId;
        $productGroup = $this->client->get('https://api.trendyol.com/webbrowsinggw/api/productGroup/' . $productGroupId);
        if ($productGroup->getStatusCode() == 200) {
            $this->group = json_decode($productGroup->getBody()->getContents());
        }
        return $this;
    }

    /**
     * Get product main slicing attributes if exists
     * @return object|null
     */
    private function getGroupAttributes(): ?object
    {
        $group = $this->group;
        if ($group && $group->result && count($group->result->slicingAttributes) > 0) {
            return $group->result->slicingAttributes[0];
        }
        return null;
    }

    /**
     * Parse all products by main attributes with loop
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function parseAttributes(): TrendYolParser
    {
        $attributes = $this->getGroupAttributes();
        if ($attributes) {
            $attributesArray = [];
            foreach ($attributes->attributes as $attribute) {
                foreach ($attribute->contents as $content) {
                    $content->name = $attributes->displayName;
                    $content->attrName = $attribute->name;
                    $content->attrBeautifiedName = $attribute->beautifiedName;
                    $attributesArray[] = $content;
                }
            }
            foreach ($attributesArray as $attribute) {
                $this->url = $this->baseUrl . $attribute->url;
                $this->parseProductByUrl();
                if ($this->parsedProductResponse) {
                    $this->parseProductGroup()
                        ->parseBaseItems()
                        ->setProduct();
                }
            }
        } else $this->setProduct();

        return $this;
    }

    /**
     * Parse product base items
     * @return $this
     */
    private function parseBaseItems(): TrendYolParser
    {
        $product = $this->parsedProductResponse->product;

        $this->response->brand = [
            'slug' => $product->brand->beautifiedName,
            'name' => $product->brand->name
        ];

        $this->response->category = [
            'name' => $product->category->name,
            'slug' => $product->category->beautifiedName
        ];

        $this->response->gender = isset($product->gender) ? $product->gender->name : null;
        $this->response->delivery = $product->deliveryInformation->deliveryDate;
        $this->response->groupId = $product->productGroupId;
        $this->response->rating = [
            'avg' => $product->ratingScore->averageRating,
            'count' => $product->ratingScore->totalRatingCount
        ];
        return $this;
    }

    /**
     * @return TrendYolParser
     */
    public static function instance(): TrendYolParser
    {
        return new self();
    }

    /**
     * @return TrendYolParser
     */
    public function setProduct(): TrendYolParser
    {
        $this->parsedProduct = new ParsedProduct();
        $product = $this->parsedProductResponse->product;
        $attachments = [];
        foreach ($product->images as $image) {
            $attachments[] = $this->imageBaseUrl . $image;
        }
        $this->parsedProduct->fill([
            'attachments' => $attachments,
            'barcode' => $product->productCode,
            'id' => $product->id,
            'color' => $product->color,
            'url' => $this->baseUrl . $product->url,
            'name' => $product->name
        ]);
        foreach ($product->variants as $variant) {
            $this->setProductVariant($variant);
        }

        foreach ($product->alternativeVariants as $alternativeVariant) {
            $this->setProductVariant($alternativeVariant);
        }

        $this->response->products[] = $this->parsedProduct;
        return $this;
    }

    /**
     * @param $variant
     */
    public function setProductVariant($variant)
    {
        $productVariant = new ProductVariant();
        $product = $this->parsedProductResponse->product;
        $discountedPrice = $variant->price->discountedPrice->value;
        $sellingPrice = $variant->price->sellingPrice->value;
        $discount = $sellingPrice > 0 ? ceil(100 - (100 * $discountedPrice / $sellingPrice)) : 0;

        $productVariant->fill([
            'barcode' => $variant->barcode,
            'stock' => $variant->stock,
            'price' => $discountedPrice,
            'old_price' => $sellingPrice,
            'discount' => $discount,
            'url' => $this->baseUrl . $product->url . ($variant->urlQuery ?? null),
            'value' => $variant->attributeValue
        ]);
        $this->parsedProduct->variants[] = $productVariant;
    }

    /**
     * @param string $slug
     * @return array
     */
    public function getAggregations(string $slug): array
    {
        try {
            $response = $this->client->get('https://api.trendyol.com/websearchgw/v2/api/aggregations/' . $slug);
            if ($response->getStatusCode() === 200) {
                $body = $response->getBody()->getContents();
                $response = json_decode($body, JSON_UNESCAPED_UNICODE);
                return $response['result']['aggregations'];
            }
        } catch (GuzzleException $e) {
        }
        return [];
    }

    /**
     * @param string $slug
     * @param int $page
     * @return array
     */
    public function getProducts(string $slug, int $page = 1): array
    {
        try {
            $response = $this->client->get('https://api.trendyol.com/websearchgw/v2/api/filter/' . $slug . '?pi=' . $page);
            if ($response->getStatusCode() === 200) {
                $body = $response->getBody()->getContents();
                $response = json_decode($body, JSON_UNESCAPED_UNICODE);
                return [
                    'products' => $response['result']['products'],
                    'total_count' => $response['result']['totalCount'],
                ];
            }
        } catch (GuzzleException $e) {
        }
        return [
            'products' => [],
            'total_count' => 0
        ];
    }
}
