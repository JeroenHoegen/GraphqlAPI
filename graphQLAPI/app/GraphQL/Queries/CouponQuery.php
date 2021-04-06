<?php


namespace App\GraphQL\Queries;

use App\Models\Webshop;
use Automattic\WooCommerce\Client;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class CouponQuery extends Query
{
    protected $attributes = [
        'name' => 'Coupons',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('coupon'));
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
        $payments = $woocommerce->get('coupons');
        $response = [];
        foreach ($payments as $payment) {
            $response[] = [
                "code" => $payment->code,
                "id" => $payment->id,
                "description" => $payment->description,
            ];
        }
        return $response;
    }
}
