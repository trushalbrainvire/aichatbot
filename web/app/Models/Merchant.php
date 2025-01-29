<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Merchant extends Model
{
    use HasUuids;

    /**
     * table columns
     *
     * @var list<string>
     */
    protected $fillable = [
        'merchant_id',
        'store',
        'owner',
        'email',
        'currency_code',
        'currency_formats',
        'address',
        'domain',
        'is_password_protected',
        'plan',
        'storefront_password',
        'user_id'
    ];



    protected $cast = [
        'currency_formats' => 'array',
        'address' => 'array'
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
    protected $foreignKey = 'user_id';


    /**
     * Get the user that owns the Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the products for the Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all of the policies for the Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }


}
