<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuspendedController extends Controller
{
    public function index()
    {
        // Prevent access if not strictly suspended
        if (auth()->check() && !auth()->user()->isSuspended()) {
            return redirect()->route('dashboard');
        }
        
        return view('pages.suspended', [
            'title' => 'Account Suspended'
        ]);
    }
}
