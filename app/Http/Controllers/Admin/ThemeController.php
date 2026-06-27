<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ThemeRequest;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    // ─── INDEX ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $themes = Theme::when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('sort_order')
            ->paginate(10)
            ->withQueryString();

        return view('admin.themes.index', compact('themes', 'search'));
    }

    // ─── CREATE ───────────────────────────────────────────────
    public function create()
    {
        return view('admin.themes.create');
    }

    // ─── STORE ────────────────────────────────────────────────
    public function store(ThemeRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        Theme::create($data);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Tematik berhasil ditambahkan.');
    }

    // ─── EDIT ─────────────────────────────────────────────────
    public function edit(Theme $theme)
    {
        return view('admin.themes.edit', compact('theme'));
    }

    // ─── UPDATE ───────────────────────────────────────────────
    public function update(ThemeRequest $request, Theme $theme)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        $theme->update($data);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Tematik berhasil diperbarui.');
    }

    // ─── DESTROY ──────────────────────────────────────────────
    public function destroy(Theme $theme)
    {
        $theme->delete();

        return redirect()->route('admin.themes.index')
            ->with('success', 'Tematik berhasil dihapus.');
    }
}
