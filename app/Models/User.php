<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'fb_id', 'fb_token', 'fb_token_exp',
    ];

    protected $dates = [
        'deleted_at', 'fb_token_exp',
    ];

    protected $casts = [
        'authorized' => 'boolean',
        'client'     => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'fb_token', 'fb_token_exp',
    ];
}
