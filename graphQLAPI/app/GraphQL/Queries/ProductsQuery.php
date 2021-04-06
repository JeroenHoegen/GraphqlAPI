<?php


namespace App\GraphQL\Queries;

use App\Models\Webshop;
use Automattic\WooCommerce\Client;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ProductsQuery extends Query
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
            'url' => [
                'name' => 'url',
                'type' => Type::string(),
                'rules' => ['required']
            ],
        ];
    }


    public function resolve($root, $args): array
    {
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
        $products = $woocommerce->get('products');
        $response = [];
        foreach ($products as $product) {
            $response[] = [
                "name" => $product->name,
                "id" => $product->id,
                "sale_price" => $product->sale_price,
                "regular_price" => $product->regular_price,
                "permalink" => $product->permalink,
                "date_created" => $product->date_created,
                "type" => $product->type,
                "description" => $product->description,
                "on_sale" => $product->on_sale,
                "status" => $product->type];
        }
        return $response;
    }
}
