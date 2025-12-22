<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ToolController extends Controller
{
    /**
     * Show the Telegram Chat ID Finder tool.
     */
    public function chatIdFinder()
    {
        return view('pages.public.tools.chat-id-finder');
    }

    /**
     * Show the App Key Generator tool.
     */
    public function appKeyGenerator()
    {
        return view('pages.public.tools.app-key-generator');
    }
}
