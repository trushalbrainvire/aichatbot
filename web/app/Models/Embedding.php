<?php

namespace App\Models;

use App\Casts\JSON;
use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\{Vector, HasNeighbors};
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Embedding extends Model
{
    use HasNeighbors;
    /**
     * table columns
     *
     * @var list<string>
     */
    protected $fillable = [
        'vectors',
        'metadata',
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
            'vectors' => Vector::class,
            'metadata'=> JSON::class,
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
    * Get the parent embeddable model (product or page).
    */
    public function embeddable(): MorphTo
    {
        return $this->morphTo();
    }
}
