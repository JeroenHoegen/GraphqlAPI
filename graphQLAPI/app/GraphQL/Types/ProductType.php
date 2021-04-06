<?php
namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProductType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Product',
        'id' => '',
        "regular_price"  => 'Price',
        "sale_price"  => 'Sale price',
        "permalink"  => 'Link',
        "date_created"   => 'Date',
        "type"  => 'Type',
        "status"  => 'Status',
        "description"  => 'desc',
        'on_sale' => false,
    ];


    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Id of a particular book',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The title of the book',
            ],
            'regular_price' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Name of the author of the book',
            ],
            'sale_price' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The language which the book was written in',
            ],
            'permalink' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The year the book was published',
            ],
            'type' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The international standard book number for the book',
            ],
            'status' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The international standard book number for the book',
            ],
            'description' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The international standard book number for the book',
            ],
            'on_sale' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'The international standard book number for the book',
            ],
        ];
    }
}
