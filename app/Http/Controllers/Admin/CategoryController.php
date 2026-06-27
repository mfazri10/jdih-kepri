<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // ─── INDEX ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $categories = Category::with('parent')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('sort_order')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'search'));
    }

    // ─── CREATE ───────────────────────────────────────────────
    public function create()
    {
        $parents = Category::orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    // ─── STORE ────────────────────────────────────────────────
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // ─── EDIT ─────────────────────────────────────────────────
    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    // ─── UPDATE ───────────────────────────────────────────────
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    // ─── DESTROY ──────────────────────────────────────────────
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
