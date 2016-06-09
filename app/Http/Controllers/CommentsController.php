<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\ForbiddenPost;
use Illuminate\Http\Request;

use App\Http\Requests;

class CommentsController extends Controller
{
    public function show($forbiddenPost, Request $request)
    {
        $forbiddenPost = ForbiddenPost::withTrashed()->findOrFail($forbiddenPost);
        if ($request->ajax())
        {

            return view('partials.comments.comments', ['post' => $forbiddenPost]);
        }

        return redirect('/');
    }

    public function create($forbiddenPost, Request $request)
    {
        $forbiddenPost = ForbiddenPost::withTrashed()->findOrFail($forbiddenPost);
        if ($request->ajax())
        {
            $validator = \Validator::make($request->all(), [
                'message' => 'required',
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    'error' => $validator->errors()->toArray()
                ]);
            }

            $created = $forbiddenPost->comments()->save(new Comment([
                'user_id' => \Auth::user()->id, 'message' => htmlspecialchars($request->get('message'))
            ]));

            $newComments = $forbiddenPost->comments()->where('id', '>', $request->get('last_comment'))->get();

            $view = ['last_comment' => $newComments->last()->id, 'html' => ""];

            foreach ($newComments as $comment)
            {
                $view['html'] .= view('partials.comments.comment', compact('comment'))->render();
            }

            return response()->json($view);
        }

        return redirect('/');
    }
}
