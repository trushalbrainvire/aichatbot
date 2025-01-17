<?php

namespace App\Models;

use Pgvector\Laravel\{Vector, HasNeighbors};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasUuids, HasNeighbors;
    /**
     * table columns
     *
     * @var list<string>
     */
    protected $fillable = [
        'embeddings',
        'product_id',
        'graphql_id',
        'title',
        'body',
        'handle',
        'productType',
        'vendor',
        'onlineStoreUrl',
        'price',
        'comparedAtPrice',
        'tags',
        'options_and_values',
        'merchant_id'
    ];

    protected $cast = [
        'category' => 'array',
        'tags'=> 'array',
        'embeddings' => Vector::class,
    ];
    /**
     * primary key of the equivalent table
     *
     * @var uuid
     */
    protected $primaryKey = 'id';

    /**
     * foreign key of the equivalent table
     *
     * @var int
     */
    protected $foreignKey = 'merchant_id';
}
