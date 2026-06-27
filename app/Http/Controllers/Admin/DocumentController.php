<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DocumentRequest;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Category;
use App\Models\Theme;
use App\Models\Tag;
use App\Models\DocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    // ─── INDEX ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $search     = trim((string) $request->input('search'));
        $typeId     = $request->input('type_id');
        $categoryId = $request->input('category_id');
        $status     = $request->input('status');
        $year       = $request->input('year');

        $documents = Document::with(['type', 'category', 'creator'])
            ->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('number', 'like', "%{$search}%")
                  ->orWhere('teu', 'like', "%{$search}%");
            })
            ->when($typeId, fn($q) => $q->where('type_id', $typeId))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($year, fn($q) => $q->where('year', $year))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $types       = DocumentType::active()->orderBy('name')->get();
        $categories  = Category::active()->orderBy('name')->get();
        $years       = Document::selectRaw('DISTINCT year')->orderByDesc('year')->pluck('year');

        return view('admin.documents.index', compact(
            'documents', 'search', 'typeId', 'categoryId', 'status', 'year',
            'types', 'categories', 'years'
        ));
    }

    // ─── CREATE ───────────────────────────────────────────────
    public function create()
    {
        $document   = new Document();
        $types      = DocumentType::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $themes     = Theme::active()->orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();

        return view('admin.documents.create', compact('document', 'types', 'categories', 'themes', 'tags'));
    }

    // ─── STORE ────────────────────────────────────────────────
    public function store(DocumentRequest $request)
    {
        $data = $request->validated();

        // Handle checkbox
        $data['is_featured'] = $request->has('is_featured');

        // Handle published_at
        if ($request->filled('published_at')) {
            $data['published_at'] = $request->input('published_at');
        }

        // Set created_by
        $data['created_by'] = auth()->id();

        // Create document
        $document = Document::create($data);

        // Sync themes
        if ($request->filled('themes')) {
            $document->themes()->sync($request->input('themes'));
            // Update documents_count on themes
            foreach ($document->themes as $theme) {
                $theme->update(['documents_count' => $theme->documents()->count()]);
            }
        }

        // Sync tags
        if ($request->filled('tags')) {
            $document->tags()->sync($request->input('tags'));
        }

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $index => $file) {
                $path = $file->store('documents', 'public');
                $document->attachments()->create([
                    'filename'      => basename($path),
                    'original_name' => $file->getClientOriginalName(),
                    'file_url'      => Storage::disk('public')->url($path),
                    'file_path'     => $path,
                    'file_size'     => $file->getSize(),
                    'mime_type'     => $file->getMimeType(),
                    'sort_order'    => $index,
                    'created_by'    => auth()->id(),
                ]);
            }
        }

        // Update category documents_count
        if ($document->category_id) {
            $document->category->update([
                'documents_count' => $document->category->documents()->count()
            ]);
        }

        // Log version
        DocumentVersion::create([
            'document_id' => $document->id,
            'user_id'     => auth()->id(),
            'old_values'  => null,
            'new_values'  => $document->toArray(),
            'created_at'  => now(),
        ]);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen hukum berhasil ditambahkan.');
    }

    // ─── SHOW ─────────────────────────────────────────────────
    public function show(Document $document)
    {
        $document->load(['type', 'category', 'themes', 'tags', 'attachments', 'creator', 'editor']);
        return view('admin.documents.show', compact('document'));
    }

    // ─── EDIT ─────────────────────────────────────────────────
    public function edit(Document $document)
    {
        $document->load(['themes', 'tags', 'attachments']);

        $types      = DocumentType::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $themes     = Theme::active()->orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();

        return view('admin.documents.edit', compact('document', 'types', 'categories', 'themes', 'tags'));
    }

    // ─── UPDATE ───────────────────────────────────────────────
    public function update(DocumentRequest $request, Document $document)
    {
        $oldValues = $document->toArray();

        $data = $request->validated();

        // Handle checkbox
        $data['is_featured'] = $request->has('is_featured');

        // Handle published_at
        if ($request->filled('published_at')) {
            $data['published_at'] = $request->input('published_at');
        }

        // Set updated_by
        $data['updated_by'] = auth()->id();

        // Update document
        $document->update($data);

        // Sync themes
        if ($request->has('themes')) {
            $document->themes()->sync($request->input('themes', []));
            // Update documents_count on themes
            foreach (\App\Models\Theme::all() as $theme) {
                $theme->update(['documents_count' => $theme->documents()->count()]);
            }
        }

        // Sync tags
        if ($request->has('tags')) {
            $document->tags()->sync($request->input('tags', []));
        }

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $index => $file) {
                $path = $file->store('documents', 'public');
                $document->attachments()->create([
                    'filename'      => basename($path),
                    'original_name' => $file->getClientOriginalName(),
                    'file_url'      => Storage::disk('public')->url($path),
                    'file_path'     => $path,
                    'file_size'     => $file->getSize(),
                    'mime_type'     => $file->getMimeType(),
                    'sort_order'    => $document->attachments()->max('sort_order') + 1,
                    'created_by'    => auth()->id(),
                ]);
            }
        }

        // Update category documents_count
        if ($document->category_id) {
            $document->category->update([
                'documents_count' => $document->category->documents()->count()
            ]);
        }

        // Log version
        DocumentVersion::create([
            'document_id' => $document->id,
            'user_id'     => auth()->id(),
            'old_values'  => $oldValues,
            'new_values'  => $document->fresh()->toArray(),
            'created_at'  => now(),
        ]);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen hukum berhasil diperbarui.');
    }

    // ─── DESTROY ──────────────────────────────────────────────
    public function destroy(Document $document)
    {
        // Delete attachment files
        foreach ($document->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        // Update category documents_count
        $categoryId = $document->category_id;

        $document->delete();

        if ($categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                $category->update(['documents_count' => $category->documents()->count()]);
            }
        }

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen hukum berhasil dihapus.');
    }

    // ─── DESTROY ATTACHMENT ───────────────────────────────────
    public function destroyAttachment(Document $document, \App\Models\Attachment $attachment)
    {
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return redirect()->back()
            ->with('success', 'Lampiran berhasil dihapus.');
    }
}
