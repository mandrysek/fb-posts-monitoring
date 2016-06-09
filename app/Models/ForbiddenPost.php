<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ForbiddenPost extends Model
{
    use SoftDeletes;

    protected $table = 'forbidden_posts';

    protected $fillable = [
        'fb_page_id', 'fb_id', 'message', 'permalink_url', 'created_time',
    ];

    protected $dates = [
        'deleted_at', 'created_time',
    ];

    public function facebookPage()
    {
        return $this->belongsTo(FacebookPage::class, 'fb_page_id', 'id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id', 'id')->orderBy('created_at', 'asc');
    }
}