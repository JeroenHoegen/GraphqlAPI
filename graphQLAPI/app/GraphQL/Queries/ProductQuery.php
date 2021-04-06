<?php


namespace App\GraphQL\Queries;

use App\Models\Webshop;
use Automattic\WooCommerce\Client;
use GraphQL\Type\Definition\Type;
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
                'type' => Type::string(),
                'rules' => ['required']
            ],
            'url' => [
                'name' => 'url',
                'type' => Type::string(),
                'rules' => ['required']
            ],
        ];
    }


    public function resolve($root, $args)
    {
        $id = $args['id'];
        $webshop = Webshop::query()->where("url", $args["url"])->get();

        $woocommerce = new Client(
            $webshop[0]->url,
            $webshop[0]->key,
            $webshop[0]->secret,
            [
                'wp_api' => true,
                'version' => 'wc/v2',
                'verify_ssl' => false,
            ]
        );
        $products = $woocommerce->get("products/$id");
        $response = [];

        $response[] = [
            "name" => $products->name,
            "id" => $products->id,
            "sale_price" => $products->sale_price,
            "regular_price" => $products->regular_price,
            "permalink" => $products->permalink,
            "date_created" => $products->date_created,
            "type" => $products->type,
            "description" => $products->description,
            "on_sale" => $products->on_sale,
            "status" => $products->type];

        return $response;
    }
}
