<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacebookPage extends Model
{
    use SoftDeletes;

    protected $table = 'fb_pages';

    protected $fillable = [
        'fb_id', 'name',
    ];

    protected $dates = [
        'deleted_at'
    ];


    public function forbiddenPosts()
    {
        return $this->hasMany(ForbiddenPost::class, 'fb_page_id', 'id');
    }

    public function allPosts() {
        return $this->hasMany(FacebookPost::class, 'fb_page_id', 'id');
    }
}
