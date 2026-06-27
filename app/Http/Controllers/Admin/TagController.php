<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // ─── INDEX ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $tags = Tag::when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tags.index', compact('tags', 'search'));
    }

    // ─── CREATE ───────────────────────────────────────────────
    public function create()
    {
        return view('admin.tags.create');
    }

    // ─── STORE ────────────────────────────────────────────────
    public function store(TagRequest $request)
    {
        $data = $request->validated();

        Tag::create($data);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag berhasil ditambahkan.');
    }

    // ─── EDIT ─────────────────────────────────────────────────
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    // ─── UPDATE ───────────────────────────────────────────────
    public function update(TagRequest $request, Tag $tag)
    {
        $data = $request->validated();

        $tag->update($data);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag berhasil diperbarui.');
    }

    // ─── DESTROY ──────────────────────────────────────────────
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag berhasil dihapus.');
    }
}
