<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ForbiddenPost extends Model
{
    protected $table = 'forbidden_posts';

    protected $fillable = [
        'fb_page_id', 'fb_id', 'message', 'permalink_url', 'created_time',
    ];

    protected $dates = [
        'created_time',
    ];


    public function facebookPage()
    {
        return $this->belongsTo(FacebookPage::class, 'fb_page_id', 'id');
    }
}