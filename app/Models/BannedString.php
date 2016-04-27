<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedString extends Model
{
    protected $table = 'banned_strings';

    protected $fillable = [
        'value',
    ];
}
