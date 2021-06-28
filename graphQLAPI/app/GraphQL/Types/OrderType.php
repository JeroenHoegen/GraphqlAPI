<?php
namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OrderType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Order',
        'listOfProducts' => "",

    ];


    public function fields(): array
    {
        return [
            'listOfProducts' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'A list of the products from the order',
            ],
        ];
    }
}
