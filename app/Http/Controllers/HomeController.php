<?php

namespace App\Http\Controllers;

use App\Models\BannedString;
use App\Models\ForbiddenPost;
use App\Models\User;
use App\Services\Facebook\FacebookManager;
use App\Services\Instagram\InstagramManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Requests;

class HomeController extends Controller
{
    public function index()
    {
        $forbiddenPosts = ForbiddenPost::orderBy('created_time', 'desc')->where('state', 'found')->get();
        $bannedStrings = BannedString::get();

        $postsByDay = $this->groupPostsByDay($forbiddenPosts);

        return view('home', compact('postsByDay', 'bannedStrings'));
    }

    public function deleted()
    {
        $deletedPosts = ForbiddenPost::onlyTrashed()->orderBy('created_time', 'desc')->get();
        $postsByDay = $this->groupPostsByDay($deletedPosts);

        return view('posts', compact('postsByDay'));
    }

    public function evaluating()
    {
        $forbiddenPosts = ForbiddenPost::orderBy('created_time', 'desc')->where('state', 'evaluating')->get();
        $postsByDay = $this->groupPostsByDay($forbiddenPosts);

        return view('posts', compact('postsByDay'));
    }

    public function forbidden()
    {
        $forbiddenPosts = ForbiddenPost::orderBy('created_time', 'desc')->where('state', 'forbidden')->get();
        $postsByDay = $this->groupPostsByDay($forbiddenPosts);

        return view('posts', compact('postsByDay'));
    }


    private function groupPostsByDay($posts)
    {
        $postsByDay = [];

        foreach ($posts as $post)
        {

            if (is_null($post->facebookPage))
            {
                continue;
            }

            $createdAt = $post->created_at->format('Y-m-d');

            if (!isset($postsByDay[$createdAt]))
            {
                $postsByDay[$createdAt] = [];
            }

            $postsByDay[$createdAt][] = $post;
        }

        krsort($postsByDay);

        return $postsByDay;
    }


}
