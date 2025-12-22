<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use App\Models\LegalPageRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegalManagementController extends Controller
{
    public function index()
    {
        $pages = LegalPage::with(['currentRevision'])->get();
        return view('pages.admin.legal.index', compact('pages'));
    }

    public function edit(LegalPage $legalPage)
    {
        $legalPage->load('currentRevision');
        return view('pages.admin.legal.edit', compact('legalPage'));
    }

    public function update(Request $request, LegalPage $legalPage)
    {
        $request->validate([
            'content' => 'required',
            'version' => 'required_unless:update_existing,true',
            'change_log' => 'nullable|string',
        ]);

        if ($request->has('update_existing') && $request->update_existing == 'true') {
            $revision = $legalPage->currentRevision;
            if ($revision) {
                $revision->update([
                    'content' => $request->content,
                    'change_log' => $request->change_log ?? $revision->change_log,
                    'created_by' => Auth::id(),
                ]);
                return redirect()->route('admin.legal-pages.index')->with('success', 'Current version updated successfully (no new revision created).');
            }
        }

        // Deactivate old revisions
        LegalPageRevision::where('legal_page_id', $legalPage->id)->update(['is_active' => false]);

        // Create new revision
        LegalPageRevision::create([
            'legal_page_id' => $legalPage->id,
            'content' => $request->content,
            'version' => $request->version,
            'change_log' => $request->change_log,
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.legal-pages.index')->with('success', 'Legal page updated successfully with a new version.');
    }
}
