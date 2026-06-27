<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DocumentTypeRequest;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    // ─── INDEX ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $documentTypes = DocumentType::with('parent')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"))
            ->orderBy('sort_order')
            ->paginate(10)
            ->withQueryString();

        return view('admin.document-types.index', compact('documentTypes', 'search'));
    }

    // ─── CREATE ───────────────────────────────────────────────
    public function create()
    {
        $parents = DocumentType::orderBy('name')->get();
        return view('admin.document-types.create', compact('parents'));
    }

    // ─── STORE ────────────────────────────────────────────────
    public function store(DocumentTypeRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        DocumentType::create($data);

        return redirect()->route('admin.document-types.index')
            ->with('success', 'Jenis dokumen berhasil ditambahkan.');
    }

    // ─── EDIT ─────────────────────────────────────────────────
    public function edit(DocumentType $documentType)
    {
        $parents = DocumentType::where('id', '!=', $documentType->id)->orderBy('name')->get();
        return view('admin.document-types.edit', compact('documentType', 'parents'));
    }

    // ─── UPDATE ───────────────────────────────────────────────
    public function update(DocumentTypeRequest $request, DocumentType $documentType)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        $documentType->update($data);

        return redirect()->route('admin.document-types.index')
            ->with('success', 'Jenis dokumen berhasil diperbarui.');
    }

    // ─── DESTROY ──────────────────────────────────────────────
    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return redirect()->route('admin.document-types.index')
            ->with('success', 'Jenis dokumen berhasil dihapus.');
    }
}
