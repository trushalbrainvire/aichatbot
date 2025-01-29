<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Page extends Model
{
    use HasUuids;
    /**
     * table columns
     *
     * @var list<string>
     */
    protected $fillable = [
        'page_id',
        'graphql_id',
        'title',
        'body',
        'handle',
        'merchant_id'
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
