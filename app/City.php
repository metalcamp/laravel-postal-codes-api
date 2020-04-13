<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * @var string
     */
    public $table = 'cities';

    /**
     * @var array
     */
    protected $cascadeDeletes = [
        'postalCodes',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'country_id',
        'province_id',
    ];

    final public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    final public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    final public function postalCodes(): HasMany
    {
        return $this->hasMany(PostalCode::class, 'city_id');
    }
}
