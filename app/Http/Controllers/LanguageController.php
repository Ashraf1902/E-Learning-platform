<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request, string $lang)
    {
        if (in_array($lang, ['ar', 'en'])) {
            Session::put('locale', $lang);
        }
        return redirect()->back();
    }
}
