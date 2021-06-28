<?php


namespace App\GraphQL\Queries;

use App\Models\Webshop;
use Automattic\WooCommerce\Client;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Grayloon\Magento\Magento;

class OrdersQuery extends Query
{
    protected $attributes = [
        'name' => 'Order',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('webshop'));
    }

    public function args(): array
    {
        return [
            'webshopID' => [
                'name' => 'webshopID',
                'type' => Type::int(),
                'rules' => ['required']
            ],
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
            ],
        ];
    }


    public function resolve($args): int
    {
        $webshop = Webshop::query()->where("id", $args["webshopID"])->get();
        $response = [];
        switch ( $webshop[0]['type']){
            case "WooCommerce";
                $response = $this->WooCommerce($webshop);
                break;
            case "Magento";
                $response = $this->Magento($webshop);
                break;
        }

        return $response;
    }

    public function WooCommerce($webshop): array
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
        $products = $woocommerce->get('orders');
        $response = [];
        foreach ($products as $product) {
            foreach ($product->line_items as $item){
                if(array_key_exists($item->id, $response)){
                    $response[$item->id]++;
                }else{
                    $response[$item->id] = 1;
                }
            }
        }
        return $response;
    }
}
