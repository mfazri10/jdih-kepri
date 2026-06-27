<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicHearing;
use Illuminate\Http\Request;

class PublicHearingController extends Controller
{
    public function index()
    {
        $hearings = PublicHearing::withCount('submissions')->latest()->paginate(15);
        return view('admin.hearings.index', compact('hearings'));
    }

    public function create()
    {
        return view('admin.hearings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'document_draft' => 'nullable|string|max:500',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'status'         => 'nullable|string|in:open,closed,archived',
            'location'       => 'nullable|string|max:255',
            'online_link'    => 'nullable|string|max:500',
        ]);

        $data['created_by'] = auth()->id();
        PublicHearing::create($data);

        return redirect()->route('admin.hearings.index')->with('success', 'Public Hearing berhasil ditambahkan.');
    }

    public function show(PublicHearing $hearing)
    {
        $hearing->load('submissions');
        return view('admin.hearings.show', compact('hearing'));
    }

    public function edit(PublicHearing $hearing)
    {
        return view('admin.hearings.edit', compact('hearing'));
    }

    public function update(Request $request, PublicHearing $hearing)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'document_draft' => 'nullable|string|max:500',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'status'         => 'nullable|string|in:open,closed,archived',
            'location'       => 'nullable|string|max:255',
            'online_link'    => 'nullable|string|max:500',
        ]);

        $hearing->update($data);
        return redirect()->route('admin.hearings.index')->with('success', 'Public Hearing berhasil diperbarui.');
    }

    public function destroy(PublicHearing $hearing)
    {
        $hearing->delete();
        return redirect()->route('admin.hearings.index')->with('success', 'Public Hearing berhasil dihapus.');
    }
}
