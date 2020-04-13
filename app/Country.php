<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;


class Country extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * @var string
     */
    protected $table = 'countries';

    /**
     * @var array
     */
    protected $cascadeDeletes = [
        'cities',
        'provinces',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'country_id');
    }

    final public function provinces(): HasMany
    {
        return $this->hasMany(Province::class, 'country_id');
    }
}
