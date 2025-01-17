<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Policy extends Model
{
    use HasUlids;
    /**
     * table columns
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'body',
        'merchant_id'
    ];

    /**
     * primary key of the equivalent table
     *
     * @var ulid
     */
    protected $primaryKey = 'id';

    /**
     * foreign key of the equivalent table
     *
     * @var uuid
     */
    protected $foreignKey = 'merchant_id';


    /**
     * Get the merchant that owns the Policy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
}
