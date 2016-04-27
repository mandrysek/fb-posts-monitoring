<?php

namespace App\Http\Controllers;

use App\Models\BannedString;
use App\Models\ForbiddenPost;
use App\Models\User;
use App\Services\Facebook\FacebookManager;
use Illuminate\Http\Request;
use App\Http\Requests;

class HomeController extends Controller
{
    public function index()
    {
        $forbiddenPosts = ForbiddenPost::orderBy('created_time', 'desc')->get();

        return view('home', compact('forbiddenPosts'));
    }
}
