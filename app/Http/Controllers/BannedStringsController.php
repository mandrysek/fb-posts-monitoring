<?php

namespace App\Http\Controllers;

use App\Models\BannedString;
use Illuminate\Http\Request;

use App\Http\Requests;

class BannedStringsController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'words' => 'required|unique:banned_strings,value',
            'parent' => 'required',
        ]);

        $parent = intval($request->get('parent'));
        $words = preg_split("/(\n)/", trim(htmlspecialchars($request->get('words'))));

        $addedWords = [];

        $parent = $parent > 0 ? BannedString::find($parent) : null;

        foreach ($words as $word)
        {
            $parsed = trim($word);

            if (!in_array($parsed, $addedWords) && !empty($parsed))
            {
                $addedWords[] = $parsed;
                $newBannedString = BannedString::create(['value' => $parsed]);

                if (!is_null($parent)) {
                    $parent->children()->save($newBannedString);
                }
            }
        }

        session()->flash('success_message', 'Words were added.');

        return redirect()->route('home');
    }
}
