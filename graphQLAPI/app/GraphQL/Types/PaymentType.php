<?php
namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PaymentType extends GraphQLType
{
    protected $attributes = [
        'name' => 'payment',
        'title' => 'title',
        'id' => 'id',
        "description"  => 'description',
    ];


    public function fields(): array
    {
        return [
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Name of the payment',
            ],
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Id of a particular book',
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The title of the payment method',
            ],
            'description' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'description of payment method',
            ],
        ];
    }
}
