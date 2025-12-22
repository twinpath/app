<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UiController extends Controller
{
    public function alerts()
    {
        return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
    }

    public function avatars()
    {
        return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
    }

    public function badges()
    {
        return view('pages.ui-elements.badges', ['title' => 'Badges']);
    }

    public function buttons()
    {
        return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
    }

    public function images()
    {
        return view('pages.ui-elements.images', ['title' => 'Images']);
    }

    public function videos()
    {
        return view('pages.ui-elements.videos', ['title' => 'Videos']);
    }

    public function formElements()
    {
        return view('pages.form.form-elements', ['title' => 'Form Elements']);
    }

    public function basicTables()
    {
        return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
    }
}
