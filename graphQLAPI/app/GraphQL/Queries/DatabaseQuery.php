<?php


namespace App\GraphQL\Queries;

use App\Models\Webshop;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class DatabaseQuery extends Query
{
    protected $attributes = [
        'name' => 'webshops',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('webshop'));
    }

    public function resolve()
    {
        return Webshop::all();
    }
}
