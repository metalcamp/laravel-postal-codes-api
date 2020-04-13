<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    public $table = 'provinces';

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
