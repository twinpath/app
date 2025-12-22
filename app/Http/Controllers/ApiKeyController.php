<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiKeyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $query = Auth::user()->apiKeys()->latest();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $apiKeys = $query->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return view('api-keys.partials.table', compact('apiKeys'))->render();
        }

        return view('api-keys.index', compact('apiKeys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $key = ApiKey::generate();

        Auth::user()->apiKeys()->create([
            'name' => $request->name,
            'key' => $key,
        ]);

        return back()->with('success', 'API Key generated successfully.')
            ->with('generated_key', $key);
    }

    public function destroy(ApiKey $apiKey)
    {
        if ($apiKey->user_id !== Auth::id()) {
            abort(403);
        }

        $apiKey->delete();

        return back()->with('success', 'API Key deleted successfully.');
    }

    public function toggle(ApiKey $apiKey)
    {
        if ($apiKey->user_id !== Auth::id()) {
            abort(403);
        }

        $apiKey->update([
            'is_active' => !$apiKey->is_active
        ]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'API Key status updated successfully.']);
        }

        return back()->with('success', 'API Key status updated successfully.');
    }

    public function regenerate(ApiKey $apiKey)
    {
        if ($apiKey->user_id !== Auth::id()) {
            abort(403);
        }

        $newKey = ApiKey::generate();

        $apiKey->update([
            'key' => $newKey,
            'last_used_at' => null, // Reset usage
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'API Key regenerated successfully.',
                'new_key' => $newKey
            ]);
        }

        return back()->with('success', 'API Key regenerated successfully.')
            ->with('generated_key', $newKey);
    }

    public function update(Request $request, ApiKey $apiKey)
    {
        if ($apiKey->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $apiKey->update([
            'name' => $request->name,
        ]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'API Key renamed successfully.']);
        }

        return back()->with('success', 'API Key renamed successfully.');
    }
}
