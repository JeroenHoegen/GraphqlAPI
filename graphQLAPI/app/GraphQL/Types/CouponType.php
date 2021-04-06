<?php
namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CouponType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Coupon',
        'code' => "",
        'id' => 0,
        "'description' "  => 'Price',
    ];


    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id of a particular book',
            ],
            'code' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The title of the book',
            ],
            'description' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Name of the author of the book',
            ],
        ];
    }
}
