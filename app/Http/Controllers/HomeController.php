<?php

namespace App\Http\Controllers;

use App\Models\BannedString;
use App\Models\ForbiddenPost;
use App\Models\User;
use App\Services\Facebook\FacebookManager;
use App\Services\Instagram\InstagramManager;
use Illuminate\Http\Request;
use App\Http\Requests;

class HomeController extends Controller
{
    public function index()
    {
        $forbiddenPosts = ForbiddenPost::orderBy('created_time', 'desc')->get();
        $bannedStrings = BannedString::get();

        return view('home', compact('forbiddenPosts', 'bannedStrings'));
    }

    public function deleteForbiddenPost(Request $request)
    {
        if ($request->ajax())
        {
            return ['deleted' => ForbiddenPost::destroy($request->get('id'))];
        }

        return redirect('/');
    }

    public function trash() {
        $deletedPosts = ForbiddenPost::onlyTrashed()->orderBy('created_time', 'desc')->get();

        return view('trash', compact('deletedPosts'));
    }

    public function restorePost(Request $request) {
        if ($request->ajax())
        {
            return ['restored' => ForbiddenPost::withTrashed()->where('id', $request->get('id'))->restore()];
        }

        return redirect('/');
    }
    
    
}
