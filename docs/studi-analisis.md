# 📋 Studi & Analisis: JDIH Kepri — Jaringan Dokumentasi dan Informasi Hukum Kepulauan Riau

> **Nama Project:** JDIH Kepri
> **Repository:** https://github.com/mfazri10/jdih-kepri
> **Tanggal:** 24 Juni 2026
> **Analisis:** Fazri (mfazri10)

---

## 📌 Daftar Isi

1. [Apa itu JDIH?](#1-apa-itu-jdih)
2. [Latar Belakang](#2-latar-belakang)
3. [Analisis Web JDIH Serupa](#3-analisis-web-jdih-serupa)
4. [Benchmark Komparatif](#4-benchmark-komparatif)
5. [Gap Analysis](#5-gap-analysis)
6. [30 Fitur Utama](#6-30-fitur-utama)
7. [Arsitektur Sistem](#7-arsitektur-sistem)
8. [Database Design](#8-database-design)
9. [Standar Nasional (BPHN)](#9-standar-nasional-bphn)
10. [Roadmap](#10-roadmap)

---

## 1. Apa itu JDIH?

**JDIH** (Jaringan Dokumentasi dan Informasi Hukum) adalah sistem pengelolaan dokumentasi dan informasi hukum berdasarkan:

- **Perpres No. 33 Tahun 2012** tentang JDIHN (Jaringan Dokumentasi dan Informasi Hukum Nasional)
- **Permenkumham No. 8 Tahun 2019** tentang Standar Pengelolaan JDIH

### Tujuan JDIH
1. Menyediakan **akses mudah** ke dokumen hukum bagi masyarakat
2. Mengelola **dokumentasi peraturan perundang-undangan** secara terstruktur
3. Menjamin **ketersediaan informasi hukum** yang akurat dan terkini
4. Mendukung **transparansi** penyelenggaraan pemerintahan

### Jenis Dokumen yang Dikelola
| No | Kategori | Contoh |
|---|---|---|
| 1 | **Peraturan Perundang-undangan** | UU, Perpu, PP, Perpres, Keppres, Perda, Perkada |
| 2 | **Monografi Hukum** | Buku, risalah rapat, hasil harmonisasi |
| 3 | **Artikel Hukum** | Jurnal, kajian, analisis hukum |
| 4 | **Putusan / Yurisprudensi** | Putusan MK, MA, PT, PN |
| 5 | **Nota Kesepahaman / MoU** | MoU antar instansi |
| 6 | **Naskah Akademik** | Naskah akademik RUU, DPRD |

---

## 2. Latar Belakang

### Problem
JDIH Kepri saat ini perlu sistem yang:
- **Mudah diakses** masyarakat untuk mencari produk hukum daerah
- **Terintegrasi** dengan JDIHN pusat (jdihn.go.id)
- **Sesuai standar BPHN** untuk metadata dan klasifikasi
- **Mobile-friendly** karena mayoritas akses dari HP
- **Terkelola dengan baik** untuk mendukung predikat JDIH terbaik

### Target Pengguna
| Segmen | Kebutuhan |
|---|---|
| **Masyarakat umum** | Cari peraturan daerah, download dokumen |
| **Aparatur sipil** | Akses cepat ke produk hukum untuk pekerjaan |
| **Mahasiswa/Peneliti** | Riset hukum, download jurnal, putusan |
| **Advokat/Hakim** | Cari yurisprudensi, peraturan terkait |
| **Admin JDIH** | Input, edit, kelola dokumen hukum |

---

## 3. Analisis Web JDIH Serupa

### 3.1 JDIHN.go.id (Portal Nasional)
**URL:** https://jdihn.go.id

| Aspek | Detail |
|---|---|
| **Fitur utama** | Pencarian terpusat semua anggota JDIH, direktori anggota, statistik pengunjung |
| **Kelebihan** | Agregasi data dari semua JDIH anggota, jurnal hukum terintegrasi |
| **Kekurangan** | UI kurang modern, tidak ada thematic browsing |
| **Unik** | Portal agregasi nasional, survey kepuasan |

### 3.2 JDIH DKI Jakarta
**URL:** https://jdih.jakarta.go.id

| Aspek | Detail |
|---|---|
| **Fitur utama** | 13 jenis dokumen, thematic regulation, view/download stats |
| **Kelebihan** | **Tematik Regulasi** (SPBE, Pendidikan, Kesehatan, dll), metadata kaya |
| **Kekurangan** | Tidak ada full-text search, tidak ada AI |
| **Unik** | Thematic browsing — user bisa browse per topik, bukan hanya per jenis dokumen |

### 3.3 JDIH Kemkomdigi
**URL:** https://jdih.komdigi.go.id

| Aspek | Detail |
|---|---|
| **Fitur utama** | AI-based search, thematic browsing, multi-language, 16 jenis dokumen |
| **Kelebihan** | **Pencarian berbasis AI**, full-text search, terjemahan multi-bahasa |
| **Kekurangan** | Desain agak ramai, navigasi kompleks |
| **Unik** | AI assistant "Larisa", multi-language (Arab, China, Inggris, Jepang, dll) |

### 3.4 JDIH Setjen DPR
**URL:** https://jdih.dpr.go.id

| Aspek | Detail |
|---|---|
| **Fitur utama** | 25+ subkategori dokumen, accessibility toolbar lengkap |
| **Kelebihan** | **Aksesibilitas terbaik** (resize text, grayscale, high contrast, screen reader) |
| **Kekurangan** | UI klasik, tidak mobile-friendly |
| **Unik** | Accessibility toolbar (untuk difabel), kategori paling granular (RUU, Naskah Akademik, Info Singkat) |

### 3.5 JDIH Kota Semarang ⭐ (JDIHN Award Winner)
**URL:** https://jdih.semarangkota.go.id

| Aspek | Detail |
|---|---|
| **Fitur utama** | E-Public Hearing, Konsultasi Hukum, Propemperda |
| **Kelebihan** | **Citizen engagement terbaik**, konsultasi hukum online, e-public hearing |
| **Kekurangan** | Database terbatas (2164 peraturan) |
| **Unik** | E-Public Hearing untuk partisipasi warga, Konsultasi Hukum online |

### 3.6 JDIH Kemenkumham
**URL:** https://jdih.kemenkumhamri.com

| Aspek | Detail |
|---|---|
| **Fitur utama** | Statistik cepat, API documentation, notifikasi update |
| **Kelebihan** | Desain modern, API untuk developer, notifikasi |
| **Kekurangan** | Kategori dokumen terbatas (3 jenis) |
| **Unik** | API documentation publik, notifikasi update peraturan |

---

## 4. Benchmark Komparatif

| Fitur | JDIHN | Jakarta | Komdigi | DPR | Semarang | Kemenkumham |
|---|---|---|---|---|---|---|
| Full-text Search | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| AI Features | ❌ | ❌ | ✅ | ❌ | ❌ | ✅ |
| Accessibility | ❌ | ❌ | ❌ | ✅✅ | ❌ | ❌ |
| Thematic Browse | ❌ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Multi-language | ❌ | ❌ | ✅ | ❌ | ✅ | ❌ |
| E-Public Hearing | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Legal Consultation | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |
| View/Download Stats | ❌ | ✅ | ❌ | ❌ | ✅ | ❌ |
| API Integration | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Doc Types | 4 | 13 | 16 | 25+ | 4 | 3 |
| Mobile Responsive | 🟡 | 🟡 | ✅ | ❌ | ✅ | ✅ |

---

## 5. Gap Analysis

### Fitur yang Ada di Kompetitor tapi Belum Umum
| Fitur | Hanya Ada di | Potensi untuk JDIH Kepri |
|---|---|---|
| Thematic Browsing | Jakarta, Komdigi | **WAJIB** — sangat user-friendly |
| AI Search | Komdigi | **SANGAT BAGUS** — diferensiasi |
| E-Public Hearing | Semarang | **BAGUS** — citizen engagement |
| Konsultasi Hukum | Semarang | **BAGUS** — layanan publik |
| Accessibility Toolbar | DPR | **WAJIB** — inklusivitas |
| Multi-language | Komdigi | Opsional — Kepri multi-etnis |
| API Documentation | Kemenkumham | **BAGUS** — integrasi |
| View/Download Stats | Jakarta, Semarang | **WAJIB** — analytics |

### Peluang Diferensiasi JDIH Kepri
1. **AI-Powered Search + Semantic** — pencarian cerdas yang memahami konteks hukum
2. **Thematic Browsing** — browse per topik (Pendidikan, Kesehatan, Pariwisata, Kelautan)
3. **E-Public Hearing** — partisipasi warga Kepri dalam pembentukan perda
4. **Mobile-First Design** — UI yang sangat responsif dan cepat di HP
5. **Dashboard Analytics** — statistik lengkap untuk admin dan publik
6. **Integrasi JDIHN Pusat** — auto-sync ke jdihn.go.id via API

---

## 6. 30 Fitur Utama

### A. PENCARIAN & JELAJAH — 8 Fitur

| # | Fitur | Impact | Effort | Keterangan |
|---|---|---|---|---|
| **1** | **Pencarian Full-Text** | 🔴 Tinggi | Tinggi | Cari di isi dokumen, bukan hanya judul. PostgreSQL full-text search atau Elasticsearch. Support boolean (AND, OR, NOT), frase eksak ("kata kunci"). |
| **2** | **Filter Multi-Parameter** | 🔴 Tinggi | Rendah | Filter berdasarkan: jenis dokumen, tahun, nomor, status (berlaku/tidak), sumber, penandatangan. Kombinasi filter sekaligus. |
| **3** | **Telusur Tematik (Thematic Browsing)** | 🔴 Tinggi | Sedang | Browse dokumen per topik: Pendidikan, Kesehatan, Pariwisata, Kelautan, Perikanan, Investasi, Ketenagakerjaan, dll. Setiap tema punya halaman sendiri dengan daftar peraturan terkait. |
| **4** | **Pencarian Lanjutan (Advanced Search)** | 🟡 Sedang | Sedang | Form pencarian detail: kata kunci + jenis + tahun + nomor + status + sumber + penandatangan + rentang tanggal. Simpan pencarian favorit. |
| **5** | **Auto-Suggest & Auto-Complete** | 🟡 Sedang | Rendah | Saat user mengetik di search box, muncul suggestion dari judul dokumen yang sudah ada. Mengurangi typo dan mempercepat pencarian. |
| **6** | **Pencarian Serupa (Related Documents)** | 🟡 Sedang | Sedang | Di halaman detail dokumen, tampilkan dokumen terkait (topik sama, jenis sama, atau yang mencabut/dicabut). AI-powered similarity. |
| **7** | **Riwayat Pencarian** | 🟢 Rendah | Rendah | Simpan history pencarian user. Quick access ke pencarian sebelumnya. Hapus history. |
| **8** | **Pencarian Populer (Trending Searches)** | 🟢 Rendah | Rendah | Tampilkan kata kunci yang paling sering dicari oleh pengunjung. Insight untuk admin tentang kebutuhan informasi hukum. |

### B. MANAJEMEN DOKUMEN — 8 Fitur

| # | Fitur | Impact | Effort | Keterangan |
|---|---|---|---|---|
| **9** | **Input Dokumen dengan Metadata BPHN** | 🔴 Tinggi | Sedang | Form input lengkap sesuai standar BPHN: nomor, tahun, tajuk entri utama (TEU), subjek hukum, status, sumber, tanggal penetapan, tanggal pengundangan, tempat terbit, penandatangan, bahasa, abstrak/isi. |
| **10** | **Upload Multi-Format** | 🔴 Tinggi | Rendah | Upload dokumen dalam format PDF, DOC, DOCX, JPG, PNG. Preview langsung di browser. Max 50MB per file. Support multiple files per dokumen. |
| **11** | **Kategori & Sub-Kategori Dokumen** | 🔴 Tinggi | Rendah | Hierarki kategori: Peraturan Perundang-undangan (UU, Perpu, PP, Perpres, Keppres, Inpres, Perda, Perkada, SE), Monografi Hukum, Artikel Hukum, Putusan, MoU, Naskah Akademik. Custom sub-kategori. |
| **12** | **Status Peraturan (Berlaku/Dicabut)** | 🔴 Tinggi | Sedang | Tracking status: Berlaku, Tidak Berlaku, Dicabut, Diubah. Link ke peraturan yang mencabut/mengubah. Visual indicator (hijau=berlaku, merah=dicabut). |
| **13** | **Versi & Riwayat Perubahan** | 🟡 Sedang | Sedang | Track semua perubahan pada dokumen: siapa yang edit, kapan, apa yang berubah. Version history. Restore ke versi sebelumnya. |
| **14** | **Bulk Import dari Excel/CSV** | 🟡 Sedang | Sedang | Import banyak dokumen sekaligus dari file Excel. Mapping kolom ke field metadata. Validasi otomatis. Cocok untuk migrasi dari sistem lama. |
| **15** | **Tag & Label Kustom** | 🟡 Sedang | Rendah | Tambah tag/label fleksibel ke dokumen (misal: "darurat", "covid-19", "investasi"). Filter by tag. Tag cloud visualization. |
| **16** | **Abstrak/Ringkasan Dokumen** | 🟡 Sedang | Rendah | Setiap dokumen punya field abstrak/ringkasan (500 karakter). Auto-generate dari isi dokumen (opsional AI). Tampilan di list view dan search result. |

### C. LAYANAN PUBLIK — 5 Fitur

| # | Fitur | Impact | Effort | Keterangan |
|---|---|---|---|---|
| **17** | **Konsultasi Hukum Online** | 🔴 Tinggi | Sedang | Masyarakat bisa ajukan pertanyaan hukum via form online. Admin/bagian hukum jawab. Publik bisa lihat Q&A yang sudah dijawab (FAQ publik). Notifikasi WA saat jawaban tersedia. |
| **18** | **E-Public Hearing (Partisipasi Publik)** | 🔴 Tinggi | Tinggi | Fitur uji publik draft peraturan. Masyarakat bisa baca draft, berikan masukan/tanggapan. Tracking jumlah partisipan. Export rekap masukan publik. |
| **19** | **Permintaan Informasi Hukum** | 🟡 Sedang | Rendah | Form permintaan dokumen/informasi hukum yang belum tersedia di sistem. Tracking status permintaan (diterima, diproses, selesai). Notifikasi ke pemohon. |
| **20** | **Langganan Notifikasi Peraturan** | 🟡 Sedang | Rendah | User daftar email/WA untuk dapat notifikasi saat ada peraturan baru sesuai kategori/topik yang diminati. Pilih kategori langganan. |
| **21** | **Umpan Balik & Survei** | 🟢 Rendah | Rendah | Rating bintang untuk setiap dokumen (1-5). Form feedback singkat. Statistik kepuasan pengunjung. Saran perbaikan. |

### D. ADMIN & ANALYTICS — 5 Fitur

| # | Fitur | Impact | Effort | Keterangan |
|---|---|---|---|---|
| **22** | **Dashboard Statistik Lengkap** | 🔴 Tinggi | Sedang | Total dokumen per kategori, per tahun, per status. Grafik tren upload. Dokumen paling banyak dilihat/didownload. Statistik pengunjung (harian, mingguan, bulanan). |
| **23** | **Statistik Dokumen (View & Download)** | 🟡 Sedang | Rendah | Hitung berapa kali setiap dokumen dilihat dan didownload. Tampilkan di halaman dokumen. Ranking dokumen populer. |
| **24** | **Manajemen User & Role** | 🟡 Sedang | Rendah | Role: Super Admin, Admin, Kontributor, Viewer. CRUD user. Set permission per role. Log aktivitas user. |
| **25** | **Audit Trail (Log Aktivitas)** | 🟡 Sedang | Rendah | Catat semua aktivitas: login, input dokumen, edit, hapus, download. Filter by user, tanggal, aktivitas. Export log. |
| **26** | **Export Laporan PDF/Excel** | 🟡 Sedang | Rendah | Export rekap dokumen ke PDF (format resmi) dan Excel. Filter berdasarkan kategori, tanggal, status. Template laporan profesional. |

### E. INTEGRASI & TEKNIS — 4 Fitur

| # | Fitur | Impact | Effort | Keterangan |
|---|---|---|---|---|
| **27** | **Integrasi API JDIHN Pusat** | 🔴 Tinggi | Tinggi | Auto-sync dokumen ke portal jdihn.go.id via API. Format metadata sesuai standar BPHN. Push notifikasi ke pusat saat dokumen baru ditambahkan. |
| **28** | **SEO & Open Graph** | 🟡 Sedang | Rendah | Optimasi SEO untuk setiap halaman dokumen (meta tags, structured data/schema.org). Open Graph tags untuk share di social media. Sitemap XML otomatis. |
| **29** | **Accessibility (Aksesibilitas)** | 🟡 Sedang | Sedang | Sesuai standar WCAG 2.1: text resize, high contrast mode, screen reader support, keyboard navigation. Toolbar aksesibilitas (seperti JDIH DPR). |
| **30** | **PWA (Progressive Web App)** | 🟡 Sedang | Sedang | Install ke home screen HP. Push notification native. Offline mode untuk view dokumen yang sudah di-cache. App-like experience. |

---

## 7. Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────┐
│                    USER LAYER                                │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │ Desktop  │  │  Mobile  │  │   PWA    │  │  API     │   │
│  │ Browser  │  │ Browser  │  │  App     │  │ Consumer │   │
│  └────┬─────┘  └────┬─────┘  └────┬─────┘  └────┬─────┘   │
└───────┼──────────────┼──────────────┼──────────────┼────────┘
        │              │              │              │
        ▼              ▼              ▼              ▼
┌─────────────────────────────────────────────────────────────┐
│              NEXT.JS 16 FRONTEND (SSR)                       │
│  Halaman Publik · Admin Panel · Search · API Docs           │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│              FASTAPI BACKEND (API Layer)                     │
│  Auth · Dokumen · Search · Konsultasi · Notifikasi          │
└────┬──────────┬──────────┬──────────┬──────────┬────────────┘
     │          │          │          │          │
     ▼          ▼          ▼          ▼          ▼
┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌──────────┐
│PostgreSQL│ │  Redis  │ │  R2/S3  │ │ Fonnte  │ │ JDIHN    │
│ (Full-   │ │ (Cache) │ │ (Files) │ │ (WA)    │ │ API      │
│  Text)   │ │         │ │         │ │         │ │ (Sync)   │
└─────────┘ └─────────┘ └─────────┘ └─────────┘ └──────────┘
```

---

## 8. Database Design

### Tabel Utama

```
┌──────────────┐     ┌──────────────────┐     ┌──────────────┐
│   documents  │     │ document_types   │     │  categories  │
│──────────────│     │──────────────────│     │──────────────│
│ id (ULID PK) │     │ id (ULID PK)     │     │ id (ULID PK) │
│ type_id FK   │────▶│ name             │     │ name         │
│ category_id  │────▶│ code (UU,PP,dll) │     │ slug         │
│ title        │     │ parent_id        │     │ description  │
│ number       │     │ description      │     │ icon         │
│ year         │     └──────────────────┘     └──────────────┘
│ status       │
│ teu          │     ┌──────────────────┐     ┌──────────────┐
│ subject      │     │   attachments    │     │  themes      │
│ source       │     │──────────────────│     │──────────────│
│ signatory    │     │ id (ULID PK)     │     │ id (ULID PK) │
│ date_set     │     │ document_id FK   │     │ name         │
│ date_publish │     │ filename         │     │ slug         │
│ abstract     │     │ file_url         │     │ description  │
│ full_text    │     │ file_size        │     └──────────────┘
│ language     │     │ mime_type        │
│ views (int)  │     └──────────────────┘     ┌──────────────┐
│ downloads    │                              │document_themes│
│ user_id FK   │     ┌──────────────────┐     │──────────────│
│ created_at   │     │    users         │     │ document_id  │
│ updated_at   │     │──────────────────│     │ theme_id FK  │
└──────────────┘     │ id (ULID PK)     │     └──────────────┘
                     │ name             │
                     │ email            │     ┌──────────────┐
                     │ phone (WA)       │     │consultations │
                     │ role             │     │──────────────│
                     │ unit             │     │ id           │
                     └──────────────────┘     │ user_id FK   │
                                              │ question     │
                                              │ answer       │
                                              │ status       │
                                              │ created_at   │
                                              └──────────────┘
```

---

## 9. Standar Nasional (BPHN)

### Metadata Wajib (Permenkumham 8/2019)
| Field | Keterangan | Contoh |
|---|---|---|
| **Nomor** | Nomor peraturan | 12 |
| **Tahun** | Tahun peraturan | 2026 |
| **Tajuk Entri Utama (TEU)** | Judul lengkap | Peraturan Daerah Provinsi Kepulauan Riau |
| **Subjek Hukum** | Topik/tema | Pendidikan, Kesehatan |
| **Jenis Dokumen** | Kategori | Perda, Perkada, SE |
| **Status** | Berlaku/Tidak | Berlaku, Dicabut |
| **Sumber** | Asal dokumen | DPRD, Gubernur |
| **Tanggal Penetapan** | Tanggal ditetapkan | 2026-01-15 |
| **Tanggal Pengundangan** | Tanggal diundangkan | 2026-01-20 |
| **Tempat Terbit** | Lokasi | Tanjungpinang |
| **Penandatangan** | Pejabat | Gubernur Kepri |
| **Bahasa** | Bahasa dokumen | Indonesia |
| **Abstrak** | Ringkasan | ... |

---

## 10. Roadmap

### Fase 1 — MVP (Minggu 1-3)
- Setup project (FastAPI + Next.js + PostgreSQL)
- CRUD dokumen dengan metadata BPHN lengkap
- Upload & preview PDF
- Pencarian dasar + filter multi-parameter
- Kategori & sub-kategori dokumen

### Fase 2 — Pencarian & Jelajah (Minggu 4-5)
- Full-text search (PostgreSQL tsvector)
- Telusur Tematik (thematic browsing)
- Auto-suggest & auto-complete
- Dokumen terkait (related documents)
- Statistik view & download

### Fase 3 — Layanan Publik (Minggu 6-7)
- Konsultasi Hukum Online
- Langganan Notifikasi (email/WA)
- E-Public Hearing (partisipasi publik)
- Permintaan Informasi Hukum
- Umpan balik & survei

### Fase 4 — Admin & Integrasi (Minggu 8-9)
- Dashboard statistik lengkap
- Manajemen user & role
- Audit trail
- Export laporan PDF/Excel
- Integrasi API JDIHN pusat

### Fase 5 — Polish & Launch (Minggu 10)
- Accessibility toolbar
- PWA (Progressive Web App)
- SEO & Open Graph
- Landing page
- Testing & deploy

---

> **JDIH Kepri** — Akses Mudah, Informasi Hukum Pasti. ⚖️
