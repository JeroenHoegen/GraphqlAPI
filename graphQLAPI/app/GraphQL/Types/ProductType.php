<?php
namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProductType extends GraphQLType
{
    protected $attributes = [
        'id' => 'id',
        'quantity' => "quantity",

    ];


    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::string(),
                'description' => 'id of a product',
            ],
            'quantity' => [
                'type' => Type::int(),
                'description' => 'quantity',
            ],
        ];
    }
}
