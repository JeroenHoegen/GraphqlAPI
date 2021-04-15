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
        return Type::listOf(GraphQL::type('product'));
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required']
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
        $webshop = Webshop::query()->where("id", $args["webshopID"])->get();
        $response = [];
        switch ( $webshop[0]['type']){
            case "WooCommerce";
                $response = $this->WooCommerce($webshop, $id);
                break;
            case "Magento";
                $response = $this->Magento($webshop);
                break;
        }
        return $response;
    }

    public function Magento($webshop): array
    {
        $magento = new Magento();
        $magento->baseUrl = $webshop[0]['url'];
        $products = $magento->api('products')->all();
        $response = [];

        foreach ($products["items"] as $product) {
            $response[] = [
                "name" => $product["name"],
                "id" => $product["id"],
                "sale_price" => $product["sku"],
                "regular_price" =>  $product["price"] ?? 0,
                "description" => $product["price"] ?? 0,
                "date_created" => $product["created_at"],
                "type" => $product["type_id"],
                "img_url" => $product["media_gallery_entries"][0]["file"]
            ];
        }
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
