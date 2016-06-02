<?php

namespace App\Http\Controllers;

use App\Models\ForbiddenPost;
use Illuminate\Http\Request;

use App\Http\Requests;

class ForbiddenPostsController extends Controller
{
    public function delete(Request $request)
    {
        $isClient = \Auth::user()->client;
        $forbiddenPost = ForbiddenPost::find($request->get('id'));

        if ($request->ajax())
        {
            if ($forbiddenPost instanceof ForbiddenPost && (!$isClient || ($isClient && $forbiddenPost->state === "evaluating")))
            {
                return ['done' => $forbiddenPost->destroy($request->get('id'))];
            } else {
                return ['done' => 0];
            }
        }

        return redirect('/');
    }

    public function restore(Request $request)
    {
        if ($request->ajax())
        {
            return ['done' => ForbiddenPost::withTrashed()->where('id', $request->get('id'))->restore() === true ? 1 : 0];
        }

        return redirect('/');
    }

    public function evaluate(Request $request)
    {
        if ($request->ajax())
        {
            try
            {
                $forbiddenPost = ForbiddenPost::find($request->get('id'));
                $forbiddenPost->state = "evaluating";

                return ['done' => $forbiddenPost->save() === true ? 1 : 0];
            } catch (\Exception $e)
            {
                return ['done' => 0];
            }
        }

        return redirect('/');
    }

    public function forbid(Request $request)
    {
        if ($request->ajax())
        {
            try
            {
                $forbiddenPost = ForbiddenPost::find($request->get('id'));
                $forbiddenPost->state = "forbidden";

                return ['done' => $forbiddenPost->save() === true ? 1 : 0];
            } catch (\Exception $e)
            {
                return ['done' => 0];
            }
        }

        return redirect('/');
    }

}
