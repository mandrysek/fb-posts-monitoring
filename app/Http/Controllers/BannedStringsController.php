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
        ]);

        $words = preg_split("/(\n)/", trim(htmlspecialchars($request->get('words'))));

        $addedWords = [];

        foreach ($words as $word)
        {
            $parsed = trim($word);

            if (!in_array($parsed, $addedWords) && !empty($parsed))
            {
                $addedWords[] = $parsed;
                BannedString::create(['value' => $parsed]);
            }
        }

        session()->flash('success_message', 'Words were added.');

        return redirect()->route('home');
    }
}
