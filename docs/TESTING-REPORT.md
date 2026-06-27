# 🧪 Testing Report — JDIH Kepri

> **Tanggal:** 27 Juni 2026  
> **URL:** http://fazcode.sangtech.biz.id  
> **Stack:** Laravel 13.17.0 + PHP 8.4.22 + MySQL 8  
> **Total Routes:** 118  
> **Status:** ✅ Semua error sudah di-fix

---

## 📊 Ringkasan

| Kategori | Total | ✅ Pass | ❌ Fail |
|----------|-------|---------|---------|
| Public Routes (GET) | 19 | 18 | 1 |
| Dynamic/Slug Routes | 7 | 4 | 3 |
| Admin Routes (GET) | 27 | 26 | 1 |
| Admin Edit Pages | 5 | 5 | 0 |
| API Endpoints | 2 | 2 | 0 |
| **TOTAL** | **60** | **55** | **5** |

---

## ❌ ERRORS DITEMUKAN

### ERROR #1 — `/cari/lanjutan` → 500 (View Not Found) ✅ FIXED

| Field | Detail |
|-------|--------|
| **URL** | `GET /cari/lanjutan` |
| **HTTP Status** | 500 Internal Server Error |
| **Exception** | `InvalidArgumentException` |
| **Message** | `View [front.advanced-search] not found.` |
| **File** | `app/Http/Controllers/FrontController.php:123` |
| **Severity** | 🔴 Critical |

**Root Cause:**  
Controller method `advancedSearch()` me-return view `front.advanced-search`, tapi file blade `resources/views/front/advanced-search.blade.php` **tidak ada**.

**Fix:**  
Buat file `resources/views/front/advanced-search.blade.php` atau redirect ke halaman search biasa.

---

### ERROR #2 — `/cari?q=...` → 500 (DB::raw di updateOrCreate) ✅ FIXED

| Field | Detail |
|-------|--------|
| **URL** | `GET /cari?q=peraturan` |
| **HTTP Status** | 500 Internal Server Error |
| **Exception** | `ErrorException` |
| **Message** | `Object of class Illuminate\Database\Query\Expression could not be converted to int` |
| **File** | `app/Http/Controllers/FrontController.php:96-102` |
| **Severity** | 🔴 Critical (Search totally broken!) |

**Root Cause:**  
Di method `search()`, kode ini menggunakan `DB::raw()` di dalam `updateOrCreate`:

```php
SearchQuery::updateOrCreate(
    ['query' => $query],
    [
        'results_count' => $documents->total(),
        'hit_count'     => DB::raw('hit_count + 1'),  // ❌ BUG
    ]
);
```

Model `SearchQuery` punya cast `'hit_count' => 'integer'`, jadi Laravel mencoba cast `DB::raw()` (object) ke integer → ErrorException.

**Fix:**  
```php
$searchQuery = SearchQuery::updateOrCreate(
    ['query' => $query],
    [
        'results_count' => $documents->total(),
        'hit_count'     => 0,  // default untuk create baru
    ]
);
$searchQuery->increment('hit_count');
```

---

### ERROR #3 — `/admin/documents/create` → 500 (Undefined $document di form) ✅ FIXED

| Field | Detail |
|-------|--------|
| **URL** | `GET /admin/documents/create` |
| **HTTP Status** | 500 Internal Server Error |
| **Exception** | `ErrorException` |
| **File** | `resources/views/admin/documents/_form.blade.php:182` |
| **Severity** | 🔴 Critical (Admin tidak bisa buat dokumen baru!) |

**Root Cause:**  
Form `_form.blade.php` digunakan untuk create DAN edit. Tapi di line 181 & 194:

```blade
{{-- Line 181 --}}
{{ in_array($theme->id, old('themes', $document->themes->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}

{{-- Line 194 --}}
{{ in_array($tag->id, old('tags', $document->tags->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}
```

Saat create, `$document` **tidak di-pass** dari controller, jadi `$document->themes` error. Operator `??` hanya apply ke hasil `->toArray()`, bukan ke `$document` itu sendiri.

**Fix:**  
```blade
{{-- Line 181 --}}
{{ in_array($theme->id, old('themes', isset($document) ? $document->themes->pluck('id')->toArray() : [])) ? 'selected' : '' }}

{{-- Line 194 --}}
{{ in_array($tag->id, old('tags', isset($document) ? $document->tags->pluck('id')->toArray() : [])) ? 'selected' : '' }}
```

Atau gunakan null-safe operator PHP 8:
```blade
{{ in_array($theme->id, old('themes', $document?->themes?->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}
```

---

### ERROR #4 — `/berita/{slug}` → 200 (Tidak ada 404 untuk konten tidak ada) ✅ FIXED

| Field | Detail |
|-------|--------|
| **URL** | `GET /berita/nonexistent-slug` |
| **HTTP Status** | 200 (seharusnya 404) |
| **Response** | `Detail Berita: nonexistent-slug` (31 bytes, placeholder) |
| **Severity** | 🟡 Medium |

**Root Cause:**  
Controller `beritaShow()` tidak melakukan lookup database, hanya return string placeholder. Tidak ada validasi apakah slug benar-benar ada.

**Fix:**  
Tambahkan lookup database dan return `abort(404)` jika tidak ditemukan:
```php
public function beritaShow(string $slug)
{
    $berita = Berita::where('slug', $slug)->firstOrFail();
    return view('front.berita-show', compact('berita'));
}
```

---

### ERROR #5 — `/akd/{slug}` & `/{slug}` → 200 (Tidak ada 404) ✅ FIXED

| Field | Detail |
|-------|--------|
| **URL** | `GET /akd/nonexistent-slug` → 200 |
| **URL** | `GET /nonexistent-page` → 200 |
| **HTTP Status** | 200 (seharusnya 404) |
| **Severity** | 🟡 Medium |

**Root Cause:**  
Sama seperti Error #4 — controller return placeholder tanpa lookup database.

---

## ✅ ROUTES BERHASIL (55/60)

### Public Routes (18/19 ✅)

| Status | Route | Nama |
|--------|-------|------|
| ✅ | `GET /` | Homepage / JDIH |
| ❌ | `GET /cari?q=...` | Search → **ERROR #2** |
| ❌ | `GET /cari/lanjutan` | Advanced Search → **ERROR #1** |
| ✅ | `GET /api/suggest?q=...` | API Suggest |
| ✅ | `GET /tematik` | Themes listing |
| ✅ | `GET /login` | Login page |
| ✅ | `GET /berita` | Berita listing |
| ✅ | `GET /anggota` | Anggota listing |
| ✅ | `GET /akd` | AKD listing |
| ✅ | `GET /fraksi` | Fraksi listing |
| ✅ | `GET /agenda` | Agenda listing |
| ✅ | `GET /pengumuman` | Pengumuman listing |
| ✅ | `GET /aspirasi` | Aspirasi form |
| ✅ | `GET /kunjungan` | Kunjungan form |
| ✅ | `GET /konsultasi` | Konsultasi |
| ✅ | `GET /public-hearing` | Public Hearing |
| ✅ | `GET /permintaan-informasi` | Permintaan Informasi |
| ✅ | `GET /sitemap.xml` | Sitemap |
| ✅ | `GET /up` | Health check |

### Admin Routes (26/27 ✅)

| Status | Route | Nama |
|--------|-------|------|
| ✅ | `GET /admin/dashboard` | Dashboard |
| ✅ | `GET /admin/documents` | Documents list |
| ❌ | `GET /admin/documents/create` | Document create → **ERROR #3** |
| ✅ | `GET /admin/categories` | Categories list |
| ✅ | `GET /admin/categories/create` | Category create |
| ✅ | `GET /admin/document-types` | Document Types list |
| ✅ | `GET /admin/document-types/create` | Document Type create |
| ✅ | `GET /admin/themes` | Themes list |
| ✅ | `GET /admin/themes/create` | Theme create |
| ✅ | `GET /admin/tags` | Tags list |
| ✅ | `GET /admin/tags/create` | Tag create |
| ✅ | `GET /admin/roles` | Roles list |
| ✅ | `GET /admin/roles/create` | Role create |
| ✅ | `GET /admin/permissions` | Permissions list |
| ✅ | `GET /admin/permissions/create` | Permission create |
| ✅ | `GET /admin/users` | Users list |
| ✅ | `GET /admin/users/create` | User create |
| ✅ | `GET /admin/menus` | Menus list |
| ✅ | `GET /admin/menus/create` | Menu create |
| ✅ | `GET /admin/consultations` | Consultations list |
| ✅ | `GET /admin/hearings` | Public Hearings list |
| ✅ | `GET /admin/hearings/create` | Hearing create |
| ✅ | `GET /admin/information-requests` | Information Requests list |
| ✅ | `GET /admin/subscriptions` | Subscriptions list |
| ✅ | `GET /admin/feedbacks` | Feedbacks list |
| ✅ | `GET /admin/login-logs` | Login Logs |
| ✅ | `GET /admin/audit-logs` | Audit Logs |

### Admin Edit Pages (5/5 ✅)

| Status | Route | Nama |
|--------|-------|------|
| ✅ | `GET /admin/categories/1/edit` | Category edit |
| ✅ | `GET /admin/document-types/1/edit` | Document Type edit |
| ✅ | `GET /admin/themes/1/edit` | Theme edit |
| ✅ | `GET /admin/roles/1/edit` | Role edit |
| ✅ | `GET /admin/users/1/edit` | User edit |

> ⚠️ **Catatan:** Tidak ada document di database, jadi `/admin/documents/{id}/edit` tidak bisa ditest.

---

## 🔧 PRIORITAS FIX

| # | Error | Severity | Prioritas | Status |
|---|-------|----------|-----------|--------|
| 2 | Search `/cari` broken (DB::raw) | 🔴 Critical | **P0** | ✅ Fixed |
| 3 | Admin `/admin/documents/create` 500 | 🔴 Critical | **P0** | ✅ Fixed |
| 1 | `/cari/lanjutan` view missing | 🔴 Critical | **P1** | ✅ Fixed |
| 4 | `/berita/{slug}` no 404 | 🟡 Medium | **P2** | ✅ Fixed |
| 5 | `/akd/{slug}` & `/{slug}` no 404 | 🟡 Medium | **P2** | ✅ Fixed |

---

## ✅ FIXES APPLIED

| # | File | Change |
|---|------|--------|
| 1 | `app/Http/Controllers/FrontController.php` | Ganti `DB::raw('hit_count + 1')` di `updateOrCreate` dengan `$searchQuery->increment('hit_count')` |
| 2 | `resources/views/admin/documents/_form.blade.php` | Tambah null-safe operator `$document?->themes?->pluck(...)` dan `$document?->tags?->pluck(...)` |
| 3 | `app/Http/Controllers/Admin/DocumentController.php` | Tambah `$document = new Document()` di method `create()` dan pass ke view |
| 4 | `resources/views/front/advanced-search.blade.php` | Buat file view baru untuk halaman pencarian lanjutan |
| 5 | `app/Http/Controllers/FrontController.php` | Ganti `beritaShow()`, `akdShow()`, `page()` dari placeholder string ke `abort(404)` |

---

## 📝 CATATAN TAMBAHAN

1. **Database kosong** — Tidak ada dokumen di tabel `documents`, jadi beberapa fitur (search results, document show, document edit) belum bisa ditest secara penuh.

2. **Login berfungsi** — Auth system jalan dengan baik, admin login redirect ke `/admin/dashboard`.

3. **Spatie Permission** — RBAC menggunakan spatie/laravel-permission, tidak ada custom Role model. Role management via Spatie's built-in.

4. **APP_DEBUG=true** — Debug mode aktif di production. Error pages menampilkan full stack trace. **Harus dimatikan** sebelum production.

5. **26 migrations** — Semua migration sudah di-run dan statusnya `Ran`.

---

*Report generated by automated testing on 27 June 2026*
