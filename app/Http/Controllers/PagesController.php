<?php

namespace App\Http\Controllers;

use App\Models\FacebookPage;
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

    public function index()
    {
        $pages = FacebookPage::withTrashed()->get();

        return view('pages', compact('pages'));
    }

    public function delete(Request $request)
    {
        if ($request->ajax())
        {
            return ['deleted' => FacebookPage::destroy($request->get('id'))];
        }

        return redirect('/');
    }

    public function restore(Request $request) {
        if ($request->ajax())
        {
            return ['restored' => FacebookPage::withTrashed()->where('id', $request->get('id'))->restore()];
        }

        return redirect('/');
    }
}
