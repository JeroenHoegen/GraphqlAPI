<?php


namespace App\GraphQL\Queries;

use App\Models\Webshop;
use Automattic\WooCommerce\Client;
use GraphQL\Type\Definition\Type;
use Grayloon\Magento\Magento;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ProductQuery extends Query
{
    protected $attributes = [
        'name' => 'products',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('webshop'));
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
            ],
            'sku' => [
                'name' => 'sku',
                'type' => Type::String(),
            ],
            'webshopID' => [
                'name' => 'webshopID',
                'type' => Type::int(),
                'rules' => ['required']
            ],
        ];
    }


    public function resolve($root, $args)
    {
        $id = $args['id'];
        $sku = $args['sku'];
        $webshop = Webshop::query()->where("id", $args["webshopID"])->get();
        $response = [];
        switch ( $webshop[0]['type']){
            case "WooCommerce";
                $response = $this->WooCommerce($webshop, $id);
                break;
            case "Magento";
                $response = $this->Magento($webshop, $sku);
                break;
        }
        return $response;
    }

    public function Magento($webshop, $sku): array
    {

        $magento = new Magento();
        $magento->token = $webshop[0]['customer_key'];
        $magento->baseUrl = $webshop[0]['url'];
        $product = $magento->api('products')->show($sku);
            $response[] = [
                "name" => $product["name"],
                "id" => $product["id"],
                "sku" => $product["sku"],
                "sale_price" => '',
                "regular_price" =>  $product["price"] ?? 0,
                "description" => $product["description"] ?? "",
                "date_created" => $product["created_at"],
                "type" => $product["type_id"],
                "img_url" => "https://magento.keyapplications.nl/pub/media/catalog/product/cache/35a302407f12a011cb427075a0275fff".$product["media_gallery_entries"][0]["file"]
            ];

        return $response;
    }

    public function WooCommerce($webshop, $id): array
    {
        $woocommerce = new Client(
            $webshop[0]['url'],
            $webshop[0]['customer_key'],
            $webshop[0]['customer_secret'],
            [
                'wp_api' => true,
                'version' => 'wc/v2',
                'verify_ssl' => false,
            ]
        );
        $product = $woocommerce->get("products/$id");

        $response[] = [
            "name" => $product->name,
            "id" => $product->id,
            "sku" => $product->id,
            "sale_price" => $product->sale_price,
            "regular_price" => $product->regular_price,
            "permalink" => $product->permalink,
            "date_created" => $product->date_created,
            "type" => $product->type,
            "description" => $product->description ?? "",
            "on_sale" => $product->on_sale,
            "status" => $product->type,
            "img_url" => $product->images[0]->src];

        return $response;
    }
}
