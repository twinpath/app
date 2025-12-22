<?php

namespace App\Http\Controllers;

use App\Models\LegalPage;
use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function show($slug)
    {
        $page = LegalPage::where('slug', $slug)
            ->where('is_active', true)
            ->with(['currentRevision'])
            ->firstOrFail();

        $revision = $page->currentRevision;

        if (!$revision) {
            abort(404, 'No active content found for this page.');
        }

        return view('pages.legal.show', compact('page', 'revision'));
    }
}
