<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    public $table = 'cities';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'country_id',
    ];

    final public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
