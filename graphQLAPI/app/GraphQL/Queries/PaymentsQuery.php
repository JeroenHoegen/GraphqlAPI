<?php


namespace App\GraphQL\Queries;

use App\Models\Webshop;
use Automattic\WooCommerce\Client;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class PaymentsQuery extends Query
{
    public function type(): Type
    {
        return Type::listOf(GraphQL::type('payment'));
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
        $products = $woocommerce->get('payment_gateways');
        $response = [];
        foreach ($products as $product) {
            $response[] = [
                "title" => $product->title,
                "id" => $product->id,
                "description" => $product->description];
        }
        return $response;
    }
}
