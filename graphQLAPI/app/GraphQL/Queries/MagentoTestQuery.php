<?php


namespace App\GraphQL\Queries;

use App\Models\Webshop;
use Automattic\WooCommerce\Client;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Grayloon\Magento\Magento;

class MagentoTestQuery extends Query
{
    protected $attributes = [
        'name' => 'products',
    ];

    public function type(): Type
    {
        return GraphQL::type('Int');
    }


    public function resolve(): int
    {
        $magento = new Magento();
        $magento->baseUrl = "https://magento.keyapplications.nl";
        $response = $magento->api('products')->all();
        //$response->json();

        return 1;
    }
}
