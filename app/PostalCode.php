<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostalCode extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    public $table = 'postal_codes';

    /**
     * @var array
     */
    protected $fillable = [
        'city_id',
        'code',
    ];

    final public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
