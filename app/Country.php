<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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
}
