<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Category;
use App\Models\Theme;
use App\Models\Tag;
use App\Models\SearchQuery;
use App\Models\SearchQueryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    /* ─── HOMEPAGE / JDIH LANDING ───────────────────────────── */
    public function jdih()
    {
        $featuredDocs = Document::with(['type', 'category'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->take(6)
            ->get();

        $latestDocs = Document::with(['type', 'category'])
            ->published()
            ->latest('published_at')
            ->take(8)
            ->get();

        $types = DocumentType::active()->orderBy('sort_order')->get();
        $categories = Category::active()->orderBy('sort_order')->get();
        $themes = Theme::active()->orderBy('sort_order')->get();

        $totalDocs = Document::where('status', 'berlaku')->count();
        $totalTypes = DocumentType::active()->count();
        $totalCategories = Category::active()->count();

        return view('front.jdih', compact(
            'featuredDocs', 'latestDocs', 'types', 'categories', 'themes',
            'totalDocs', 'totalTypes', 'totalCategories'
        ));
    }

    /* ─── SEARCH RESULTS ────────────────────────────────────── */
    public function search(Request $request)
    {
        $query = trim((string) $request->input('q'));
        $typeId = $request->input('type_id');
        $categoryId = $request->input('category_id');
        $year = $request->input('year');
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $documents = Document::with(['type', 'category'])
            ->published()
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->whereFullText('title', $query, ['mode' => 'boolean'])
                        ->orWhere('title', 'like', "%{$query}%")
                        ->orWhere('abstract', 'like', "%{$query}%")
                        ->orWhere('teu', 'like', "%{$query}%")
                        ->orWhere('number', 'like', "%{$query}%");
                });
            })
            ->when($typeId, fn($q) => $q->where('type_id', $typeId))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($year, fn($q) => $q->where('year', $year))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($dateFrom, fn($q) => $q->where('date_publish', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->where('date_publish', '<=', $dateTo))
            ->latest('published_at')
            ->paginate(15)
            ->withQueryString();

        // Log search
        if ($query) {
            SearchQueryLog::create([
                'query'         => $query,
                'results_count' => $documents->total(),
                'user_id'       => auth()->id(),
                'ip_address'    => $request->ip(),
                'filters'       => array_filter([
                    'type_id'     => $typeId,
                    'category_id' => $categoryId,
                    'year'        => $year,
                    'status'      => $status,
                ]),
                'searched_at'   => now(),
            ]);

            // Update or create search query stats
            $searchQuery = SearchQuery::updateOrCreate(
                ['query' => $query],
                [
                    'results_count' => $documents->total(),
                ]
            );
            $searchQuery->increment('hit_count');
        }

        $types = DocumentType::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $years = Document::selectRaw('DISTINCT year')->orderByDesc('year')->pluck('year');
        $popularSearches = SearchQuery::orderByDesc('hit_count')->take(10)->get();

        return view('front.search', compact(
            'documents', 'query', 'typeId', 'categoryId', 'year', 'status',
            'dateFrom', 'dateTo', 'types', 'categories', 'years', 'popularSearches'
        ));
    }

    /* ─── ADVANCED SEARCH ───────────────────────────────────── */
    public function advancedSearch(Request $request)
    {
        $types = DocumentType::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $years = Document::selectRaw('DISTINCT year')->orderByDesc('year')->pluck('year');

        return view('front.advanced-search', compact('types', 'categories', 'years'));
    }

    /* ─── AUTO-SUGGEST (AJAX) ───────────────────────────────── */
    public function suggest(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $suggestions = Document::where('status', 'berlaku')
            ->where('title', 'like', "%{$q}%")
            ->select('id', 'title', 'slug', 'number', 'year')
            ->limit(8)
            ->get()
            ->map(fn($doc) => [
                'id'     => $doc->id,
                'title'  => $doc->title,
                'slug'   => $doc->slug,
                'number' => $doc->number,
                'year'   => $doc->year,
                'url'    => route('front.jdih.show', $doc->slug),
            ]);

        return response()->json($suggestions);
    }

    /* ─── DOCUMENT DETAIL ───────────────────────────────────── */
    public function jdihShow($slug)
    {
        $document = Document::with(['type', 'category', 'themes', 'tags', 'attachments', 'creator'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $document->increment('views_count');

        // Related documents (same category or themes)
        $relatedDocs = Document::with(['type'])
            ->where('id', '!=', $document->id)
            ->where('status', 'berlaku')
            ->where(function ($q) use ($document) {
                if ($document->category_id) {
                    $q->where('category_id', $document->category_id);
                }
                if ($document->type_id) {
                    $q->orWhere('type_id', $document->type_id);
                }
            })
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('front.show', compact('document', 'relatedDocs'));
    }

    /* ─── DOWNLOAD ATTACHMENT ───────────────────────────────── */
    public function jdihDownload($slug, $attachmentId)
    {
        $document = Document::where('slug', $slug)->firstOrFail();
        $attachment = $document->attachments()->findOrFail($attachmentId);

        $attachment->increment('download_count');
        $document->increment('downloads_count');

        return response()->download(
            storage_path('app/public/' . $attachment->file_path),
            $attachment->original_name
        );
    }

    /* ─── THEMATIC BROWSING ─────────────────────────────────── */
    public function themes()
    {
        $themes = Theme::active()
            ->orderBy('sort_order')
            ->withCount(['documents' => fn($q) => $q->where('status', 'berlaku')])
            ->get();

        return view('front.themes', compact('themes'));
    }

    public function themeShow($slug)
    {
        $theme = Theme::where('slug', $slug)->firstOrFail();

        $documents = Document::with(['type', 'category'])
            ->whereHas('themes', fn($q) => $q->where('themes.id', $theme->id))
            ->published()
            ->latest('published_at')
            ->paginate(15);

        return view('front.theme-show', compact('theme', 'documents'));
    }

    /* ─── CATEGORY BROWSING ─────────────────────────────────── */
    public function categoryShow($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $documents = Document::with(['type'])
            ->where('category_id', $category->id)
            ->published()
            ->latest('published_at')
            ->paginate(15);

        return view('front.category-show', compact('category', 'documents'));
    }

    /* ─── DOCUMENT TYPE BROWSING ────────────────────────────── */
    public function typeShow($code)
    {
        $type = DocumentType::where('code', $code)->firstOrFail();

        $documents = Document::with(['category'])
            ->where('type_id', $type->id)
            ->published()
            ->latest('published_at')
            ->paginate(15);

        return view('front.type-show', compact('type', 'documents'));
    }

    /* ─── CONSULTATIONS (PLACEHOLDER) ───────────────────────── */
    public function consultations()
    {
        return view('front.consultations');
    }

    /* ─── PUBLIC HEARINGS (PLACEHOLDER) ─────────────────────── */
    public function hearings()
    {
        return view('front.hearings');
    }

    /* ─── INFORMATION REQUESTS (PLACEHOLDER) ────────────────── */
    public function infoRequests()
    {
        return view('front.info-requests');
    }

    /* ─── LEGACY ROUTES ─────────────────────────────────────── */
    public function index()
    {
        return redirect()->route('front.jdih');
    }

    public function berita()
    {
        return 'Halaman Berita';
    }

    public function beritaShow($slug)
    {
        abort(404);
    }

    public function anggota()
    {
        return 'Halaman Anggota';
    }

    public function akd()
    {
        return 'Halaman AKD';
    }

    public function akdShow($slug)
    {
        abort(404);
    }

    public function fraksi()
    {
        return 'Halaman Fraksi';
    }

    public function agenda()
    {
        return 'Halaman Agenda';
    }

    public function pengumuman()
    {
        return 'Halaman Pengumuman';
    }

    public function aspirasi()
    {
        return 'Halaman Aspirasi';
    }

    public function aspirasiStore(Request $request)
    {
        return back()->with('success', 'Aspirasi berhasil disimpan.');
    }

    public function kunjungan()
    {
        return 'Halaman Kunjungan';
    }

    public function kunjunganStore(Request $request)
    {
        return back()->with('success', 'Kunjungan berhasil disimpan.');
    }

    public function getRegencies($province_id)
    {
        return response()->json([]);
    }

    public function page($slug)
    {
        abort(404);
    }
}
