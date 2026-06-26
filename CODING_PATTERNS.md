# 📐 Pattern Coding — Laravel Monolith DPRD Kepri

> Dokumen ini adalah **referensi resmi** konvensi dan pola coding yang dipakai di project ini.
> Setiap kontributor **wajib** mengikuti pola ini agar codebase tetap konsisten.

---

## Daftar Isi

1. [Struktur Direktori](#1-struktur-direktori)
2. [Migration Pattern](#2-migration-pattern)
3. [Model Pattern](#3-model-pattern)
4. [FormRequest Pattern](#4-formrequest-pattern)
5. [Controller Pattern — Admin CRUD](#5-controller-pattern--admin-crud)
6. [Controller Pattern — Frontend](#6-controller-pattern--frontend)
7. [Route Pattern](#7-route-pattern)
8. [Blade Pattern — Admin Views](#8-blade-pattern--admin-views)
9. [Blade Pattern — Frontend Views](#9-blade-pattern--frontend-views)
10. [Naming Convention](#10-naming-convention)
11. [Settings / Konfigurasi Dinamis](#11-settings--konfigurasi-dinamis)
12. [RBAC & Permission Pattern](#12-rbac--permission-pattern)
13. [Checklist Modul Baru](#13-checklist-modul-baru)

---

## 1. Struktur Direktori

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          ← semua controller admin CMS
│   │   └── FrontController.php  ← satu controller untuk semua halaman publik
│   └── Requests/
│       └── Admin/          ← FormRequest per modul admin
├── Models/                 ← satu file per model
│
database/
├── migrations/             ← format: YYYY_MM_DD_HHMMSS_create_{table}_table.php
└── seeders/
    ├── MenuSeeder.php      ← seed menu sidebar admin
    └── RolePermissionSeeder.php
│
resources/views/
├── components/             ← komponen UI reusable (e.g. table, alert)
├── layouts/
│   ├── front.blade.php     ← layout publik (navbar + footer)
│   ├── admin.blade.php     ← layout admin panel
│   └── footer.blade.php    ← JS injection (bukan footer konten)
├── front/                  ← views halaman publik
│   ├── home.blade.php
│   ├── berita.blade.php
│   └── show.blade.php
└── admin/
    └── {modul}/            ← satu subfolder per modul
        ├── index.blade.php
        ├── create.blade.php
        └── edit.blade.php
│
public/
├── front/
│   ├── css/                ← CSS per halaman frontend
│   │   ├── home.css        ← styles khusus home
│   │   ├── berita.css
│   │   └── show.css
│   └── js/                 ← JS per halaman frontend
│       ├── home.js
│       ├── berita.js
│       └── show.js
│
routes/
├── web.php                 ← route publik + auth
├── admin.php               ← entry point admin (require sub-files)
└── admin/
    ├── sliders.php
    ├── features.php        ← satu file route per modul admin
    └── ...
```

---

## 2. Migration Pattern

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('icon')->default('box'); // feather icon name
            $table->string('url')->nullable();
            $table->integer('order_no')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // highlighted dark blue card
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
```

**Aturan:**

- Kolom boolean: `is_active`, `is_featured`, `is_public`, `is_published` — selalu `boolean + default`
- Kolom urutan: selalu `order_no integer default(0)`
- `timestamps()` wajib di semua tabel data
- Kolom opsional: selalu `->nullable()`
- Beri komentar `// penjelasan` di kolom yang tidak self-explanatory

---

## 3. Model Pattern

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feature extends Model
{
    // ─── FILLABLE ─────────────────────────────────────────────
    protected $fillable = [
        'name',
        'description',
        'icon',
        'url',
        'order_no',
        'is_active',
        'is_featured',
    ];

    // ─── CASTS ────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'is_active'    => 'boolean',
            'is_featured'  => 'boolean',
            'order_no'     => 'integer',
            'published_at' => 'datetime',
        ];
    }

    // ─── LOCAL SCOPES ─────────────────────────────────────────
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    // ─── RELATIONSHIPS ────────────────────────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
```

**Aturan:**

- Urutan blok: `$fillable` → `$casts` → `scopeXxx()` → relasi
- Nama scope: `scopeActive`, `scopeFeatured`, `scopePublished` (camelCase, awalan `scope`)
- Boolean field **wajib** di-cast ke `'boolean'`
- Datetime field **wajib** di-cast ke `'datetime'`
- Gunakan return type hint: `Builder`, `BelongsTo`, `HasMany`, dll.

---

## 4. FormRequest Pattern

```php
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // permission dicek di route middleware
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon'        => 'required|string|max:50',
            'url'         => 'nullable|string|max:255',
            'order_no'    => 'nullable|integer',
            'is_active'   => 'nullable',   // checkbox: ada = true, tidak ada = false
            'is_featured' => 'nullable',
        ];
    }
}
```

**Aturan:**

- Selalu taruh di `App\Http\Requests\Admin\`
- `authorize()` selalu `return true` — permission diurus route middleware
- Kolom checkbox: `'nullable'` (bukan `'boolean'`), diproses di controller dengan `$request->has('field')`
- File upload: **tidak** masuk rules, diproses manual di controller

---

## 5. Controller Pattern — Admin CRUD

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeatureRequest;
use App\Models\Feature;
use Illuminate\Support\Facades\Storage;

class FeatureController extends Controller
{
    // ─── INDEX ────────────────────────────────────────────────
    public function index()
    {
        $features = Feature::orderBy('order_no')->paginate(10);
        return view('admin.features.index', compact('features'));
    }

    // ─── CREATE ───────────────────────────────────────────────
    public function create()
    {
        return view('admin.features.create');
    }

    // ─── STORE ────────────────────────────────────────────────
    public function store(FeatureRequest $request)
    {
        $data = $request->validated();

        // Handle checkbox boolean
        $data['is_active']   = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        // Handle file upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('features', 'public');
        }

        Feature::create($data);

        return redirect()->route('admin.features.index')
            ->with('success', 'Fitur unggulan berhasil ditambahkan.');
    }

    // ─── EDIT ─────────────────────────────────────────────────
    public function edit(Feature $feature)
    {
        return view('admin.features.edit', compact('feature'));
    }

    // ─── UPDATE ───────────────────────────────────────────────
    public function update(FeatureRequest $request, Feature $feature)
    {
        $data = $request->validated();
        $data['is_active']   = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        // Handle file upload: hapus lama, simpan baru
        if ($request->hasFile('image')) {
            if ($feature->image) {
                Storage::disk('public')->delete($feature->image);
            }
            $data['image'] = $request->file('image')->store('features', 'public');
        }

        $feature->update($data);

        return redirect()->route('admin.features.index')
            ->with('success', 'Fitur unggulan berhasil diperbarui.');
    }

    // ─── DESTROY ──────────────────────────────────────────────
    public function destroy(Feature $feature)
    {
        if ($feature->image) {
            Storage::disk('public')->delete($feature->image);
        }

        $feature->delete();

        return redirect()->route('admin.features.index')
            ->with('success', 'Fitur unggulan berhasil dihapus.');
    }
}
```

**Aturan:**

- Pakai **Route Model Binding** (`Feature $feature`) — jangan `Feature::findOrFail($id)`
- Selalu `$request->validated()` bukan `$request->all()`
- Flash message: `->with('success', '...')` berhasil | `->with('error', '...')` gagal
- Checkbox: `$request->has('field')` bukan cast
- File: simpan ke `storage/public/{modul}/`, hapus file lama sebelum simpan baru

---

## 6. Controller Pattern — Frontend

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    /* ─── HOMEPAGE ────────────────────────────────────────────── */
    public function index()
    {
        $features = \App\Models\Feature::active()->orderBy('order_no')->get();

        $latestPosts = Post::with(['category', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(8)
            ->get();

        return view('front.home', compact('features', 'latestPosts'));
    }

    /* ─── DETAIL ──────────────────────────────────────────────── */
    public function beritaShow($slug)
    {
        $post = Post::with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail(); // 404 otomatis

        $post->increment('views_count');

        return view('front.show', compact('post'));
    }

    /* ─── LIST + SEARCH ───────────────────────────────────────── */
    public function berita(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $posts = Post::with(['category', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('front.berita', compact('posts', 'search'));
    }
}
```

**Aturan:**

- Komentar `/* ─── NAMA METHOD ─── */` memisahkan setiap method
- Selalu filter: `where('status', 'published')` + `where('published_at', '<=', now())`
- Selalu `->withQueryString()` pada pagination jika ada filter
- Gunakan `->when($condition, fn($q) => ...)` untuk filter kondisional
- `firstOrFail()` untuk detail — Laravel otomatis 404

---

## 7. Route Pattern

### `routes/admin.php` — Entry Point

```php
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    require __DIR__.'/admin/dashboard.php';
    require __DIR__.'/admin/sliders.php';
    require __DIR__.'/admin/features.php';  // ← tambah di sini
    // ...
});
```

### `routes/admin/features.php` — Per Modul

```php
<?php

use App\Http\Controllers\Admin\FeatureController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:features.view')->group(function () {
    Route::resource('features', FeatureController::class)->except(['show']);
});
```

**Tabel Route Name Standar:**

| Method | URL Pattern                     | Route Name               |
| ------ | ------------------------------- | ------------------------ |
| GET    | `admin/features`                | `admin.features.index`   |
| GET    | `admin/features/create`         | `admin.features.create`  |
| POST   | `admin/features`                | `admin.features.store`   |
| GET    | `admin/features/{feature}/edit` | `admin.features.edit`    |
| PUT    | `admin/features/{feature}`      | `admin.features.update`  |
| DELETE | `admin/features/{feature}`      | `admin.features.destroy` |

---

## 8. Blade Pattern — Admin Views

### `admin/{modul}/index.blade.php`

```blade
@extends('layouts.admin')
@section('title', 'Daftar Fitur Unggulan')
@section('page_title', 'Fitur Unggulan')

@section('page_actions')
    <a href="{{ route('admin.features.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="feather-plus"></i> Tambah Fitur
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama Fitur</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($features as $item)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $item->name }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.features.edit', $item) }}"
                                   class="btn btn-sm btn-light border">
                                    <i class="feather-edit text-warning"></i>
                                </a>
                                <form action="{{ route('admin.features.destroy', $item) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light border">
                                        <i class="feather-trash-2 text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="feather-grid fs-2 d-block mb-2 opacity-50"></i>
                                Belum ada data fitur unggulan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $features->links() }}</div>
    </div>
@endsection
```

### Reusable Components (Komponen Berbagi Pakai)

Untuk meminimalkan redundansi markup HTML (seperti membungkus tabel, rendering pagination, form search, dan empty state), wajib memisahkannya ke dalam Blade Components di dalam folder `resources/views/components/`.

Contoh pemanggilan komponen tabel seragam `<x-table.table>`:

```blade
<x-table.table 
    title="Daftar Fitur" 
    :headers="['Nama Fitur', 'Status', 'Aksi']" 
    :records="$features"
    :searchRoute="route('admin.features.index')"
    :searchValue="$search"
>
    @foreach ($features as $item)
    <tr>
        <td class="ps-4 fw-semibold">{{ $item->name }}</td>
        <td>
            <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                {{ $item->is_active ? 'Aktif' : 'Draft' }}
            </span>
        </td>
        <td class="pe-4 text-end">
            <!-- Aksi Buttons -->
        </td>
    </tr>
    @endforeach
</x-table.table>
```

### `admin/{modul}/create.blade.php`

```blade
@extends('layouts.admin')
@section('title', 'Tambah Fitur Unggulan')
@section('page_title', 'Tambah Fitur Unggulan')

@section('content')
<form action="{{ route('admin.features.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            {{-- Error bag --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Field dengan validasi inline --}}
            <div class="mb-3">
                <label class="form-label">Nama Fitur <span class="text-danger">*</span></label>
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="Cth: E-Legislasi"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Checkbox toggle --}}
            <div class="mb-3">
                <label class="form-label d-block">Status Publikasi</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input"
                           type="checkbox"
                           name="is_active"
                           id="is_active"
                           value="1"
                           checked>
                    <label class="form-check-label" for="is_active">
                        Aktifkan (Tampilkan di halaman)
                    </label>
                </div>
            </div>

            {{-- File upload --}}
            <div class="mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" class="form-control" name="image" accept="image/*">
                <div class="form-text">Format: JPG/PNG/WEBP. Maks 2MB.</div>
            </div>

            <hr class="my-4">

            {{-- Action buttons — SELALU di bawah --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="feather-save me-1"></i> Simpan
                </button>
                <a href="{{ route('admin.features.index') }}" class="btn btn-light border px-4">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
```

### `admin/{modul}/edit.blade.php`

```blade
@extends('layouts.admin')
@section('title', 'Edit Fitur Unggulan')
@section('page_title', 'Edit Fitur Unggulan')

@section('content')
<form action="{{ route('admin.features.update', $feature) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')   {{-- WAJIB untuk update --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">Nama Fitur <span class="text-danger">*</span></label>
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $feature->name) }}"  {{-- old() + fallback model --}}
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Preview gambar existing --}}
            @if($feature->image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $feature->image) }}"
                         alt="Preview"
                         style="max-height: 120px; object-fit: cover;"
                         class="img-thumbnail">
                    <div class="form-text">Upload gambar baru untuk mengganti.</div>
                </div>
            @endif

            {{-- Checkbox edit: old() → fallback model --}}
            <div class="form-check form-switch mb-3">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_active"
                       id="is_active"
                       value="1"
                       {{ old('is_active', $feature->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Aktif</label>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="feather-save me-1"></i> Perbarui
                </button>
                <a href="{{ route('admin.features.index') }}" class="btn btn-light border px-4">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
```

---

## 9. Blade Pattern — Frontend Views

> [!IMPORTANT]
> **CSS dan JS halaman frontend WAJIB dipisah ke file terpisah** di `public/front/css/` dan `public/front/js/`.
> **Dilarang** menulis `<style>` atau `<script>` inline di dalam blade view — kecuali untuk satu baris konfigurasi variabel JS yang memang perlu data dari server.

> [!IMPORTANT]
> **Dilarang** memanggil `\App\Models\Xxx::method()` langsung dari view blade frontend.
> Semua data — termasuk settings, relasi, dan query — **wajib disiapkan di FrontController** dan diteruskan ke view via `compact()`.

### Alur Data Frontend

```
FrontController::index()
    ↓ query semua data yang dibutuhkan view
    ↓ termasuk Setting::getVal() untuk semua key
    ↓ return view('front.home', compact(...))
         ↓
    home.blade.php — hanya menerima dan menampilkan $variabel
```

### Struktur Umum Halaman

```blade
@extends('layouts.front')

{{-- ✅ BENAR: panggil file CSS terpisah --}}
@section('styles')
    <link rel="stylesheet" href="{{ asset('front/css/home.css') }}">
@endsection

@section('content')

    {{-- ═══ NAMA SECTION ═══ --}}
    {{-- Pemisah section dengan separator ═══ --}}

    {{-- ✅ BENAR: $sliders sudah disiapkan controller --}}
    @if ($sliders->isNotEmpty())
        {{-- Konten jika ada data --}}
    @else
        {{-- Fallback jika kosong --}}
    @endif

    {{-- ✅ BENAR: $sambutan sudah disiapkan controller sebagai array/object --}}
    <section class="section-pad">
        <div class="container">

            {{-- Section Header — Pola standar --}}
            <div class="text-center mb-5 fade-up">
                <h6 class="text-uppercase fw-bold mb-2 text-muted"
                    style="letter-spacing: 3px; font-size: 0.85rem;">
                    Label Kecil
                </h6>
                <h2 class="mb-3 font-serif"
                    style="font-size: clamp(2rem, 4.5vw, 2.75rem); font-weight: 700;">
                    Judul Section
                </h2>
            </div>

            {{-- Data loop --}}
            @forelse($items as $item)
                <div>{{ $item->name }}</div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="feather-inbox fs-1 mb-2 d-block opacity-25"></i>
                    <p>Belum ada data yang tersedia.</p>
                </div>
            @endforelse

        </div>
    </section>

@endsection

{{-- ✅ BENAR: panggil file JS terpisah --}}
@section('scripts')
    <script src="{{ asset('front/js/home.js') }}"></script>
@endsection
```

### File CSS/JS Terpisah

**`public/front/css/home.css`** — tempat semua style khusus halaman home:

```css
/* ═══ HOME PAGE STYLES ═══ */

.hover-up:hover {
    transform: translateY(-8px);
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.125) !important;
}

.gallery-card:hover img {
    transform: scale(1.1);
}

@media (min-width: 768px) {
    .overlap-section {
        margin-top: -65px !important;
        z-index: 30;
    }
}
```

**`public/front/js/home.js`** — tempat semua interaktivitas halaman home:

```js
/* ═══ HOME PAGE SCRIPTS ═══ */

document.addEventListener("DOMContentLoaded", function () {
    // Animasi fade-up saat scroll
    const fadeEls = document.querySelectorAll(".fade-up");
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add("visible");
                    observer.unobserve(e.target);
                }
            });
        },
        { threshold: 0.1 },
    );
    fadeEls.forEach((el) => observer.observe(el));
});
```

> **Pengecualian yang diizinkan:** Variabel JS yang memerlukan data server (mis. URL dinamis dari controller) boleh ditulis inline **satu blok saja** di atas `<script src>`, menggunakan `data-*` attribute atau variabel global yang minimal:
>
> ```blade
> @section('scripts')
>     <script>
>         // ⚠️ Hanya variabel konfigurasi dari server — logika tetap di file .js
>         window.AppConfig = {
>             beritaUrl: "{{ route('front.berita') }}",
>             assetUrl:  "{{ asset('storage') }}",
>         };
>     </script>
>     <script src="{{ asset('front/js/home.js') }}"></script>
> @endsection
> ```

### Komponen Berulang Frontend

#### Card Berita

```blade
<div class="card border-0 shadow-sm hover-up h-100" style="border-radius: 16px; overflow: hidden;">
    @if($post->featured_image)
        <div style="height: 200px; overflow: hidden;">
            <img src="{{ Storage::url($post->featured_image) }}"
                 alt="{{ $post->title }}"
                 class="w-100 h-100 object-fit-cover"
                 style="transition: transform 0.4s;">
        </div>
    @endif
    <div class="card-body p-4">
        <span class="badge bg-light text-muted text-uppercase mb-2"
              style="font-size: 0.65rem; font-weight: 600;">
            {{ $post->category?->name ?? 'Umum' }}
        </span>
        <h5 class="fw-bold mb-2" style="font-size: 1rem; line-height: 1.4;">
            <a href="{{ route('front.berita.show', $post->slug) }}"
               class="text-decoration-none text-dark">
                {{ $post->title }}
            </a>
        </h5>
        <small class="text-muted">
            <i class="feather-clock me-1"></i>
            {{ $post->published_at?->translatedFormat('d M Y') ?? $post->created_at->translatedFormat('d M Y') }}
        </small>
    </div>
</div>
```

#### Empty State (Kolom Penuh)

```blade
<div class="col-12 text-center text-muted py-5">
    <i class="feather-{icon} fs-1 mb-2 d-block opacity-25"></i>
    <p>Belum ada {nama data} yang tersedia.</p>
</div>
```

#### Badge Status

```blade
{{-- Gunakan konsisten --}}
<span class="badge bg-success">Aktif</span>
<span class="badge bg-secondary">Draft</span>
<span class="badge bg-warning text-dark">Pending</span>
<span class="badge bg-primary">Featured</span>
<span class="badge bg-light text-muted border">Tidak</span>
```

---

## 10. Naming Convention

### File & Folder

| Komponen         | Konvensi                                     | Contoh                                        |
| ---------------- | -------------------------------------------- | --------------------------------------------- |
| Model            | PascalCase                                   | `Feature.php`, `LegalDocument.php`            |
| Controller Admin | `{Model}Controller.php`                      | `FeatureController.php`                       |
| FormRequest      | `{Model}Request.php`                         | `FeatureRequest.php`                          |
| Migration        | `YYYY_MM_DD_HHMMSS_create_{table}_table.php` | `2026_06_03_000004_create_features_table.php` |
| View Admin       | `admin/{modul}/{action}.blade.php`           | `admin/features/index.blade.php`              |
| View Front       | `front/{halaman}.blade.php`                  | `front/home.blade.php`                        |
| Route Admin      | `routes/admin/{modul}.php`                   | `routes/admin/features.php`                   |

### Kolom Database

| Tipe            | Konvensi                | Contoh                                     |
| --------------- | ----------------------- | ------------------------------------------ |
| Primary key     | `id`                    | —                                          |
| Foreign key     | `{model}_id`            | `category_id`, `author_id`                 |
| Boolean status  | `is_{status}`           | `is_active`, `is_featured`, `is_published` |
| Urutan tampilan | `order_no`              | —                                          |
| Tanggal publish | `published_at`          | —                                          |
| File path       | nama field tanpa prefix | `image`, `thumbnail`, `photo`              |

### Variable Blade

| Konteks            | Konvensi   | Contoh                                   |
| ------------------ | ---------- | ---------------------------------------- |
| Collection         | plural     | `$features`, `$posts`, `$sliders`        |
| Item dalam loop    | singular   | `$feature`, `$post`, `$slide`            |
| Model di edit view | nama model | `$feature`                               |
| Var PHP di view    | camelCase  | `$settingPhoto`, `$photoUrl`, `$namaVal` |

### Route Name

```
admin.{modul}.{action}   → admin.features.index
front.{halaman}          → front.berita
front.{halaman}.{action} → front.berita.show
```

---

## 11. Settings / Konfigurasi Dinamis

> [!CAUTION]
> **DILARANG** memanggil `\App\Models\Setting::getVal()` langsung dari view blade frontend.
> Setting harus diambil di **FrontController**, lalu diteruskan ke view melalui `compact()`.
> Pengecualian: layout file (`layouts/front.blade.php`) boleh mengakses Setting karena ia
> digunakan di semua halaman dan tidak bisa dilayani oleh satu controller saja.

### ❌ SALAH — Jangan lakukan ini di view frontend

```blade
{{-- ❌ Memanggil model langsung dari blade --}}
{{ \App\Models\Setting::getVal('contact_email') }}

@php
    $namaVal = \App\Models\Setting::getVal('sambutan_ketua_nama', 'Pimpinan DPRD');
@endphp
```

### ✅ BENAR — Siapkan di Controller, terima di View

**Di `FrontController.php`:**

```php
public function index()
{
    // Ambil semua setting yang dibutuhkan view di sini
    $sambutan = [
        'nama'              => Setting::getVal('sambutan_ketua_nama', 'Pimpinan DPRD'),
        'jabatan'           => Setting::getVal('sambutan_ketua_jabatan', 'Ketua DPRD'),
        'teks'              => Setting::getVal('sambutan_ketua_teks', 'Selamat datang...'),
        'foto'              => Setting::getVal('sambutan_ketua_foto'),
        'title'             => Setting::getVal('sambutan_ketua_title', 'Selamat Datang'),
        'title_highlight'   => Setting::getVal('sambutan_ketua_title_highlight', 'DPRD Kepri'),
        'button1_label'     => Setting::getVal('sambutan_ketua_button1_label', 'Profil Pimpinan'),
        'button1_url'       => Setting::getVal('sambutan_ketua_button1_url', '/anggota'),
        'button2_label'     => Setting::getVal('sambutan_ketua_button2_label', 'Sampaikan Aspirasi'),
        'button2_url'       => Setting::getVal('sambutan_ketua_button2_url', '/aspirasi'),
    ];

    $features    = Feature::active()->orderBy('order_no')->get();
    $latestPosts = Post::with(['category'])->published()->latest('published_at')->take(8)->get();

    return view('front.home', compact('sambutan', 'features', 'latestPosts'));
}
```

**Di `front/home.blade.php`:**

```blade
{{-- ✅ Terima variabel dari controller — tidak ada model call --}}
<h2>{{ $sambutan['title'] }}
    <span class="highlight">{{ $sambutan['title_highlight'] }}</span>
</h2>
<p>{{ $sambutan['teks'] }}</p>
<h5>{{ $sambutan['nama'] }}</h5>
<p>{{ $sambutan['jabatan'] }}</p>

@if($sambutan['foto'])
    <img src="{{ asset('storage/' . $sambutan['foto']) }}" alt="Pimpinan">
@endif
```

### Naming Key Settings

Format: `{grup}_{sub}_{field}`

```
site_name               site_logo               site_tagline
contact_address         contact_email           contact_phone
social_facebook         social_instagram        social_twitter     social_youtube
sambutan_ketua_nama     sambutan_ketua_foto     sambutan_ketua_teks
footer_description      footer_exp_1_label      footer_exp_1_url
seo_meta_title          seo_meta_description    seo_ga4_id
```

### Admin Settings Form

```blade
{{-- Input text --}}
<input type="text"
       name="settings[footer_description]"
       class="form-control"
       value="{{ old('settings.footer_description', $settings['footer_description']->value ?? '') }}">

{{-- Textarea --}}
<textarea name="settings[contact_address]"
          class="form-control"
          rows="3">{{ old('settings.contact_address', $settings['contact_address']->value ?? '') }}</textarea>

{{-- File upload → diproses terpisah, BUKAN lewat settings[] --}}
<input type="file" name="site_logo" accept="image/*">
```

---

## 12. RBAC & Permission Pattern

### Mendaftarkan Permission Baru di `config/rbac.php`

```php
// Di array semua permissions
'features.view',
'features.create',
'features.update',
'features.delete',

// Di role admin
'admin' => [
    // ... existing permissions
    'features.view',
    'features.create',
    'features.update',
    'features.delete',
],
```

### Di Route

```php
Route::middleware('permission:features.view')->group(function () {
    Route::resource('features', FeatureController::class)->except(['show']);
});
```

### Di MenuSeeder

```php
Menu::query()->updateOrCreate(
    ['category' => 'cms', 'name' => 'Fitur Unggulan', 'parent_id' => $landingpage->id],
    [
        'feature_name' => 'features',              // nama permission (tanpa .view)
        'url'          => null,
        'route_name'   => 'admin.features.index',
        'icon'         => 'feather-grid',
        'order'        => 3,
        'is_active'    => true,
    ]
);
```

### Setelah Daftarkan

```bash
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=MenuSeeder
```

---

## 13. Checklist Modul Baru

Gunakan checklist ini setiap kali menambah modul admin CRUD baru:

```
DATABASE
[ ] Buat migration: database/migrations/YYYY_MM_DD_HHMMSS_create_{table}_table.php
[ ] php artisan migrate

BACKEND
[ ] Model: app/Models/{Model}.php
    [ ] $fillable
    [ ] $casts (boolean, datetime)
    [ ] scopeActive() jika ada is_active
[ ] FormRequest: app/Http/Requests/Admin/{Model}Request.php
[ ] Controller: app/Http/Controllers/Admin/{Model}Controller.php
    [ ] index, create, store, edit, update, destroy

ROUTES
[ ] Buat file: routes/admin/{modul}.php
[ ] Daftarkan di routes/admin.php: require __DIR__.'/admin/{modul}.php';

RBAC
[ ] Tambah permissions di config/rbac.php (view, create, update, delete)
[ ] php artisan db:seed --class=RolePermissionSeeder

ADMIN VIEWS
[ ] resources/views/admin/{modul}/index.blade.php
[ ] resources/views/admin/{modul}/create.blade.php
[ ] resources/views/admin/{modul}/edit.blade.php

MENU
[ ] Tambah entry di MenuSeeder.php
[ ] php artisan db:seed --class=MenuSeeder

FRONTEND (jika perlu tampil di landing page)
[ ] Update FrontController.php: tambah query + pass ke compact()
[ ] Update home.blade.php atau view terkait

VERIFY
[ ] php artisan route:list --name=admin.{modul}
[ ] php artisan view:cache (pastikan tidak ada syntax error)
[ ] Test di browser: index, create, edit, delete
```

---

_Dokumen ini dibuat berdasarkan kode aktual project blog-dprd-kepri — Juni 2026_
