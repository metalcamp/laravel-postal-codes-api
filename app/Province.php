<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * @var string
     */
    public $table = 'provinces';

    protected $cascadeDeletes = [
        'cities',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'country_id',
        'name',
    ];

    final public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    final public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'province_id');
    }
}
