<?php

namespace App\Http\Controllers;

use App\Services\Facebook\FacebookManager;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function add(Request $request)
    {
        $this->validate($request, [
            'page_id' => 'required|unique:fb_pages,fb_id',
        ]);

        $facebookManager = new FacebookManager();

        $page = $facebookManager->getFacebookPageInfo($request->get('page_id'), auth()->user()->fb_token);

        if (is_null($page))
        {
            return redirect()->back()->withInputs($request->only('page_id'))->withErrors([
                'page_id' => 'Facebook page with id '.$request->get('page_id').' does not exist.',
            ]);
        }

        $page->save();

        session()->flash('success_message', 'Words were added.');

        return redirect()->route('home');
    }
}
