# 📋 Daftar Fitur & Pekerjaan — JDIH Kepri

> **Last Updated:** 27 Juni 2026
> **Total Fitur:** 52 item
> **Total Tabel DB:** 42 tabel
> **Tech Stack:** Laravel 13 + MySQL 8.4 + Bootstrap 5

---

## Daftar Isi

1. [Ringkasan Prioritas](#1-ringkasan-prioritas)
2. [Phase 1 — Foundation & Auth](#2-phase-1--foundation--auth)
3. [Phase 2 — Manajemen Dokumen (Core)](#3-phase-2--manajemen-dokumen-core)
4. [Phase 3 — Pencarian & Jelajah](#4-phase-3--pencarian--jelajah)
5. [Phase 4 — Layanan Publik](#5-phase-4--layanan-publik)
6. [Phase 5 — Admin & Analytics](#6-phase-5--admin--analytics)
7. [Phase 6 — Workflow & Approval](#7-phase-6--workflow--approval)
8. [Phase 7 — Integrasi & Teknis](#8-phase-7--integrasi--teknis)
9. [Mapping Tabel → Fitur](#9-mapping-tabel--fitur)
10. [Dependencies](#10-dependencies)

---

## 1. Ringkasan Prioritas

| Prioritas | Jumlah | Keterangan |
|---|---|---|
| 🔴 **Wajib (MVP)** | 20 fitur | Tanpa ini sistem tidak bisa dipakai |
| 🟡 **Penting** | 22 fitur | Nilai tambah signifikan |
| 🟢 **Nice to Have** | 10 fitur | Bisa ditambah belakangan |

### Status per Phase

| Phase | Nama | Fitur | Status |
|---|---|---|---|
| 1 | Foundation & Auth | 8 | ⬜ Belum |
| 2 | Manajemen Dokumen | 12 | ⬜ Belum |
| 3 | Pencarian & Jelajah | 8 | ⬜ Belum |
| 4 | Layanan Publik | 8 | ⬜ Belum |
| 5 | Admin & Analytics | 8 | ⬜ Belum |
| 6 | Workflow & Approval | 5 | ⬜ Belum |
| 7 | Integrasi & Teknis | 3 | ⬜ Belum |

---

## 2. Phase 1 — Foundation & Auth

> **Tabel:** `users`, `roles`, `permissions`, `role_permissions`, `login_logs`, `menus`
> **Estimasi:** Minggu 1-2

| # | Fitur | Prioritas | Tabel | Keterangan |
|---|---|---|---|---|
| 1.1 | **Autentikasi (Login/Register/Logout)** | 🔴 | `users`, `sessions` | Login email+password, remember me, session management |
| 1.2 | **Role & Permission (RBAC)** | 🔴 | `roles`, `permissions`, `role_permissions` | Spatie Permission. Role: super-admin, admin, operator, viewer, public |
| 1.3 | **Manajemen User (CRUD)** | 🔴 | `users` | Tambah/edit/hapus user, assign role, activate/deactivate |
| 1.4 | **Dynamic Menu Sidebar** | 🟡 | `menus` | Menu admin bisa diatur dari DB. Parent-child. Permission-based visibility |
| 1.5 | **Login Log (Audit)** | 🟡 | `login_logs` | Catat setiap login: user, IP, status (success/failed), timestamp |
| 1.6 | **Profil User** | 🟡 | `users` | Edit nama, email, phone, avatar, password |
| 1.7 | **Lupa Password (Reset)** | 🟡 | `users`, `password_reset_tokens` | Email reset link |
| 1.8 | **Middleware & Guard** | 🔴 | - | Auth middleware, role middleware, permission middleware, idle timeout |

**Deliverables:**
- [ ] Login page (3 style: cover, creative, minimal)
- [ ] Register page
- [ ] Admin dashboard (layout + sidebar)
- [ ] User management CRUD
- [ ] Role & permission management
- [ ] Menu management CRUD

---

## 3. Phase 2 — Manajemen Dokumen (Core)

> **Tabel:** `documents`, `document_types`, `categories`, `status`, `themes`, `document_themes`, `document_versions`, `document_relations`, `tags`, `document_tags`, `document_attachments`, `comments`
> **Estimasi:** Minggu 2-4

| # | Fitur | Prioritas | Tabel | Keterangan |
|---|---|---|---|---|
| 2.1 | **CRUD Dokumen Hukum** | 🔴 | `documents` | Input/edit/hapus dokumen dengan metadata BPHN lengkap: nomor, tahun, TEU, subjek, jenis, status, sumber, tanggal, penandatangan |
| 2.2 | **Upload Multi-Format** | 🔴 | `document_attachments` | Upload PDF, DOC, DOCX, JPG, PNG. Max 50MB. Multiple files per dokumen |
| 2.3 | **Preview Dokumen** | 🔴 | `document_attachments` | Preview PDF di browser (PDF.js). Preview gambar inline |
| 2.4 | **Kategori & Sub-Kategori** | 🔴 | `categories` | Hierarki: parent → child. Icon per kategori. Materialized path untuk query cepat |
| 2.5 | **Jenis Dokumen (Type)** | 🔴 | `document_types` | UU, PP, Perpres, Perda, Perkada, SK, SE, Instruksi, Himbauan. Hierarki (UU > PP) |
| 2.6 | **Status Peraturan** | 🔴 | `status` | Berlaku, Dicabut, Tidak Berlaku. Visual indicator (hijau/merah/kuning) |
| 2.7 | **Telusur Tematik (Themes)** | 🔴 | `themes`, `document_themes` | Browse per topik: Pendidikan, Kesehatan, Kelautan, Pariwisata, Investasi, dll |
| 2.8 | **Tag & Label Kustom** | 🟡 | `tags`, `document_tags` | Tag fleksibel untuk klasifikasi tambahan. Tag cloud di frontend |
| 2.9 | **Versi & Riwayat Perubahan** | 🟡 | `document_versions` | Track semua perubahan: siapa, kapan, apa yang berubah. Restore versi |
| 2.10 | **Dokumen Terkait (Relations)** | 🟡 | `document_relations` | Link antar dokumen: mencabut, mengubah, mengatur_tentang, terkait |
| 2.11 | **Bulk Import dari Excel/CSV** | 🟡 | `documents` | Import massal dokumen via file Excel. Template download. Validasi otomatis |
| 2.12 | **Abstrak/Ringkasan** | 🟡 | `documents` | Field abstrak per dokumen. Tampilkan di listing dan detail |

**Deliverables:**
- [ ] Form input dokumen (lengkap sesuai BPHN)
- [ ] List dokumen (table + grid view)
- [ ] Detail dokumen (public view)
- [ ] Upload & preview attachment
- [ ] Category management CRUD
- [ ] Document type management CRUD
- [ ] Theme management CRUD
- [ ] Tag management CRUD
- [ ] Bulk import wizard

---

## 4. Phase 3 — Pencarian & Jelajah

> **Tabel:** `document_search_index`, `search_queries`, `search_query_logs`
> **Estimasi:** Minggu 4-5

| # | Fitur | Prioritas | Tabel | Keterangan |
|---|---|---|---|---|
| 3.1 | **Pencarian Full-Text** | 🔴 | `documents`, `document_search_index` | Cari di judul, abstrak, full_text. MySQL FULLTEXT. Support boolean (AND, OR, NOT) |
| 3.2 | **Filter Multi-Parameter** | 🔴 | - | Filter: jenis dokumen, tahun, kategori, status, tema, sumber, penandatangan. Kombinasi |
| 3.3 | **Pencarian Lanjutan (Advanced)** | 🟡 | - | Form detail: kata kunci + jenis + tahun + nomor + status + rentang tanggal |
| 3.4 | **Auto-Suggest & Auto-Complete** | 🟡 | `search_queries` | Suggestion dari judul dokumen saat mengetik. Trending searches |
| 3.5 | **Pencarian Serupa (Related)** | 🟡 | `document_relations` | Di halaman detail, tampilkan dokumen terkait berdasarkan kategori, tema, tag |
| 3.6 | **Riwayat Pencarian** | 🟢 | `search_query_logs` | Simpan history pencarian user. Quick access ke pencarian sebelumnya |
| 3.7 | **Pencarian Populer (Trending)** | 🟢 | `search_queries` | Tampilkan kata kunci paling sering dicari. Insight untuk admin |
| 3.8 | **Highlight Hasil Pencarian** | 🟡 | - | Highlight kata kunci yang cocok di snippet hasil pencarian |

**Deliverables:**
- [ ] Search bar di header (public + admin)
- [ ] Search results page dengan filter sidebar
- [ ] Advanced search form
- [ ] Auto-suggest dropdown
- [ ] Related documents section di detail page
- [ ] Popular searches widget

---

## 5. Phase 4 — Layanan Publik

> **Tabel:** `consultations`, `public_hearings`, `hearing_submissions`, `information_requests`, `subscriptions`, `feedbacks`, `notifications`
> **Estimasi:** Minggu 5-7

| # | Fitur | Prioritas | Tabel | Keterangan |
|---|---|---|---|---|
| 4.1 | **Konsultasi Hukum Online** | 🔴 | `consultations` | Masyarakat bisa tanya jawab hukum. Admin jawab. Tampilkan FAQ publik |
| 4.2 | **E-Public Hearing** | 🔴 | `public_hearings`, `hearing_submissions` | Partisipasi publik dalam pembentukan perda. Upload draft, terima masukan |
| 4.3 | **Permintaan Informasi Hukum** | 🟡 | `information_requests` | Form permintaan informasi (PP 61/2010). Register number otomatis. Deadline 10 hari kerja |
| 4.4 | **Langganan Notifikasi** | 🟡 | `subscriptions` | Subscribe per kategori/jenis dokumen. Notifikasi via email atau WA saat dokumen baru |
| 4.5 | **Umpan Balik & Survei** | 🟢 | `feedbacks` | Form feedback: saran, masalah, pujian. Rating 1-5 bintang. Admin reply |
| 4.6 | **Notifikasi In-App** | 🟡 | `notifications` | Notifikasi di admin panel: dokumen baru, deadline, approval request |
| 4.7 | **Notifikasi Email** | 🟡 | `notifications` | Kirim notifikasi via email untuk subscription, konsultasi dijawab |
| 4.8 | **Notifikasi WhatsApp** | 🟢 | `notifications` | Kirim notifikasi via WA (Fonnte API). Reminder deadline, dokumen baru |

**Deliverables:**
- [ ] Konsultasi hukum form (public)
- [ ] Konsultasi management (admin)
- [ ] Public hearing page (public)
- [ ] Hearing management (admin)
- [ ] Information request form (public)
- [ ] Information request management (admin)
- [ ] Subscription form (public)
- [ ] Notification center (admin)
- [ ] Feedback form (public)
- [ ] Feedback management (admin)

---

## 6. Phase 5 — Admin & Analytics

> **Tabel:** `document_statistics`, `document_analytics`, `user_analytics`, `user_activities`, `audit_logs`
> **Estimasi:** Minggu 7-8

| # | Fitur | Prioritas | Tabel | Keterangan |
|---|---|---|---|---|
| 6.1 | **Dashboard Statistik Lengkap** | 🔴 | `document_statistics`, `document_analytics`, `user_analytics` | Total dokumen, views, downloads, users aktif. Chart per bulan/tahun |
| 6.2 | **Statistik per Dokumen** | 🟡 | `document_statistics` | View count, download count, shares, bookmarks per dokumen. Chart per hari |
| 6.3 | **Statistik View & Download** | 🟡 | `document_analytics` | Unique visitors, total views, downloads, avg read duration. Traffic sources |
| 6.4 | **User Analytics** | 🟢 | `user_analytics` | Aktivitas user: dokumen dilihat, di-download, pencarian, waktu |
| 6.5 | **Audit Trail (Log Aktivitas)** | 🟡 | `audit_logs` | Log semua perubahan data: siapa, kapan, apa yang diubah, nilai lama vs baru |
| 6.6 | **User Activity Log** | 🟡 | `user_activities` | Log aktivitas user: login, view, download, search, comment |
| 6.7 | **Export Laporan PDF** | 🟡 | - | Export statistik ke PDF. Template laporan resmi |
| 6.8 | **Export Laporan Excel** | 🟡 | - | Export data dokumen, statistik, user ke Excel/CSV |

**Deliverables:**
- [ ] Admin dashboard dengan chart (ApexCharts)
- [ ] Document statistics page
- [ ] User analytics page
- [ ] Audit trail page
- [ ] Export PDF/Excel button di setiap laporan

---

## 7. Phase 6 — Workflow & Approval

> **Tabel:** `workflow_templates`, `workflow_steps`, `workflow_instances`, `workflow_step_instances`, `workflow_approvals`
> **Estimasi:** Minggu 8-9

| # | Fitur | Prioritas | Tabel | Keterangan |
|---|---|---|---|---|
| 7.1 | **Workflow Template** | 🟡 | `workflow_templates`, `workflow_steps` | Template alur persetujuan per jenis dokumen. Step-by-step: review → approval |
| 7.2 | **Workflow Instance** | 🟡 | `workflow_instances`, `workflow_step_instances` | Jalankan workflow untuk dokumen. Track status: pending → in_progress → completed |
| 7.3 | **Approval/Reject** | 🟡 | `workflow_approvals` | Admin approve/reject di setiap step. Comment. Auto-escalate timeout |
| 7.4 | **Bookmark Dokumen** | 🟢 | `bookmarks` | User bisa bookmark dokumen untuk akses cepat |
| 7.5 | **Riwayat Baca** | 🟢 | `reading_history` | Track dokumen yang sudah dibaca user. Progress percentage |

**Deliverables:**
- [ ] Workflow template management
- [ ] Workflow instance tracking
- [ ] Approval action page
- [ ] My bookmarks page
- [ ] My reading history page

---

## 8. Phase 7 — Integrasi & Teknis

> **Tabel:** `api_keys`, `api_requests`
> **Estimasi:** Minggu 9-10

| # | Fitur | Prioritas | Tabel | Keterangan |
|---|---|---|---|---|
| 8.1 | **Integrasi API JDIHN Pusat** | 🔴 | - | Sync dokumen ke jdihn.go.id via API. Auto-push dokumen baru |
| 8.2 | **SEO & Open Graph** | 🟡 | - | Meta tags, OG image, sitemap.xml, robots.txt. Schema.org LegalDocument |
| 8.3 | **Accessibility (WCAG 2.1)** | 🟡 | - | Accessibility toolbar: resize text, grayscale, high contrast, screen reader |
| 8.4 | **API Publik (REST)** | 🟢 | `api_keys`, `api_requests` | API untuk integrasi eksternal. API key management. Rate limiting |
| 8.5 | **PWA (Progressive Web App)** | 🟢 | - | Install ke HP, push notification, offline cache |
| 8.6 | **Full-Text Search Index** | 🟡 | `document_search_index` | MySQL FULLTEXT index. Auto-reindex saat dokumen diubah |
| 8.7 | **Caching (Redis)** | 🟢 | - | Cache dokumen populer, statistik, search results |
| 8.8 | **Queue (Laravel Queue)** | 🟢 | `jobs`, `failed_jobs` | Proses berat di background: bulk import, notifikasi, reindex |

**Deliverables:**
- [ ] JDIHN API integration module
- [ ] SEO meta tags di semua halaman
- [ ] Sitemap.xml generator
- [ ] Accessibility toolbar
- [ ] API documentation (Swagger/OpenAPI)
- [ ] PWA manifest + service worker

---

## 9. Mapping Tabel → Fitur

| Tabel | Fitur Terkait |
|---|---|
| `users` | 1.1, 1.2, 1.3, 1.6, 1.7 |
| `roles` | 1.2 |
| `permissions` | 1.2 |
| `role_permissions` | 1.2 |
| `login_logs` | 1.5 |
| `menus` | 1.4 |
| `documents` | 2.1, 2.12, 3.1, 3.5, 6.1 |
| `document_types` | 2.5 |
| `categories` | 2.4 |
| `status` | 2.6 |
| `themes` | 2.7 |
| `document_themes` | 2.7 |
| `document_versions` | 2.9 |
| `document_relations` | 2.10, 3.5 |
| `tags` | 2.8 |
| `document_tags` | 2.8 |
| `document_attachments` | 2.2, 2.3 |
| `comments` | - |
| `consultations` | 4.1 |
| `public_hearings` | 4.2 |
| `hearing_submissions` | 4.2 |
| `information_requests` | 4.3 |
| `subscriptions` | 4.4 |
| `feedbacks` | 4.5 |
| `notifications` | 4.6, 4.7, 4.8 |
| `workflow_templates` | 7.1 |
| `workflow_steps` | 7.1 |
| `workflow_instances` | 7.2 |
| `workflow_step_instances` | 7.2 |
| `workflow_approvals` | 7.3 |
| `document_search_index` | 3.1, 8.6 |
| `search_queries` | 3.4, 3.7 |
| `search_query_logs` | 3.6 |
| `bookmarks` | 7.4 |
| `reading_history` | 7.5 |
| `user_activities` | 6.6 |
| `audit_logs` | 6.5 |
| `document_statistics` | 6.1, 6.2 |
| `document_analytics` | 6.1, 6.3 |
| `user_analytics` | 6.1, 6.4 |
| `api_keys` | 8.4 |
| `api_requests` | 8.4 |

---

## 10. Dependencies

```
Phase 1 (Auth)
    │
    ├──→ Phase 2 (Dokumen) ──→ Phase 3 (Pencarian)
    │         │
    │         ├──→ Phase 4 (Layanan Publik)
    │         │
    │         └──→ Phase 5 (Analytics)
    │                   │
    │                   └──→ Phase 6 (Workflow)
    │
    └──→ Phase 7 (Integrasi)
```

### Urutan Implementasi yang Disarankan

1. **Phase 1** — Foundation (harus pertama, semua phase butuh auth)
2. **Phase 2** — Dokumen Core (inti sistem, paling kritis)
3. **Phase 3** — Pencarian (butuh dokumen sudah ada)
4. **Phase 4** — Layanan Publik (butuh dokumen + user)
5. **Phase 5** — Analytics (butuh data dari phase 2-4)
6. **Phase 6** — Workflow (butuh dokumen + user + role)
7. **Phase 7** — Integrasi (terakhir, polish & ekstensi)

---

### Rekomendasi MVP (Minimum Viable Product)

Untuk launch pertama, **minimal** butuh:

- [x] Phase 1 (Auth & User) — **wajib**
- [ ] Phase 2 items: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6 — **wajib**
- [ ] Phase 3 items: 3.1, 3.2 — **wajib**
- [ ] Phase 4 items: 4.1 — **wajib** (konsultasi hukum)
- [ ] Phase 5 items: 6.1 — **wajib** (dashboard)
- [ ] Phase 7 items: 8.2 — **wajib** (SEO)

**Total MVP:** ~20 fitur, ~25 tabel, ~4-5 minggu

---

> **JDIH Kepri** — Akses Mudah, Informasi Hukum Pasti. ⚖️
