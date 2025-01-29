<?php

namespace App\Models;

use App\Casts\JSON;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasUuids;
    /**
     * table columns
     *
     * @var list<string>
     */
    protected $fillable = [
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'options_and_values' => JSON::class,
            'tags'=> JSON::class,
        ];
    }

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

    /**
     * Get the merchant that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * Get the product's embedding.
     */
    public function embedding()
    {
        return $this->morphOne(Embedding::class, 'embeddable');
    }
}
