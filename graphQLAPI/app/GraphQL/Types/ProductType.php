<?php
namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProductType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Product',
        'id' => '',
        'sku'=> '',
        "regular_price"  => 'Price',
        "sale_price"  => 'Sale price',
        "permalink"  => 'Link',
        "date_created"   => 'Date',
        "type"  => 'Type',
        "status"  => 'Status',
        "description"  => 'desc',
        'on_sale' => false,
        'img_url' => '',
    ];


    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Id of a particular webshop',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the webshop',
            ],
            'regular_price' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'the normal price of a webshop',
            ],
            'sale_price' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The sale price of a webshop',
            ],
            'permalink' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The link to the webshop',
            ],
            'type' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The type of a webshop',
            ],
            'status' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The status of a webshop',
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'The description of a webshop',
            ],
            'sku' => [
                'type' => Type::string(),
                'description' => 'The sku of a webshop',
            ],
            'on_sale' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'A boolean that checks if the webshop is on sale',
            ],
            'img_url' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'url to the image of a webshop',
            ],
        ];
    }
}
