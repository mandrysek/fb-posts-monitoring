<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedString extends Model
{
    protected $table = 'banned_strings';

    protected $fillable = [
        'value',
    ];

    public function children() {
        return $this->belongsToMany(BannedString::class, 'banned_strings_combs', 'parent_id', 'child_id');
    }

    public function parents() {
        return $this->belongsToMany(BannedString::class, 'banned_strings_combs', 'child_id', 'parent_id');
    }


}
