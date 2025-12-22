<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function calendar()
    {
        return view('pages.calender', ['title' => 'Calendar']);
    }


    public function blank()
    {
        return view('pages.blank', ['title' => 'Blank']);
    }

    public function error404()
    {
        return view('pages.errors.error-404', ['title' => 'Error 404']);
    }

    public function php()
    {
        phpinfo();
    }
}
