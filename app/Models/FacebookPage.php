<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPage extends Model
{
    protected $table = 'fb_pages';

    protected $fillable = [
        'fb_id', 'name',
    ];


    public function forbiddenPosts()
    {
        return $this->hasMany(ForbiddenPost::class, 'fb_page_id', 'id');
    }
}
