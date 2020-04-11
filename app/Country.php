<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'countries';

    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    final public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'country_id');
    }
}
