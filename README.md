# ⚖️ JDIH Kepri

> Jaringan Dokumentasi dan Informasi Hukum Kepulauan Riau

## 🎯 Tentang

**JDIH Kepri** adalah sistem pengelolaan dokumentasi dan informasi hukum untuk Provinsi Kepulauan Riau, sesuai standar **Perpres No. 33/2012** dan **Permenkumham No. 8/2019**.

### Fitur Unggulan
- 🔍 **Pencarian Full-Text** — cari di isi dokumen, bukan hanya judul
- 📂 **Telusur Tematik** — browse per topik (Pendidikan, Kesehatan, Kelautan, dll)
- 💬 **Konsultasi Hukum Online** — tanya jawab hukum dengan admin
- 📊 **Dashboard Analytics** — statistik lengkap untuk publik dan admin
- 📱 **Mobile-First** — responsif dan cepat di semua perangkat

## 📋 Jenis Dokumen

| Kategori | Contoh |
|---|---|
| Peraturan Perundang-undangan | UU, Perpu, PP, Perpres, Perda, Perkada, SE |
| Monografi Hukum | Buku, risalah rapat, hasil harmonisasi |
| Artikel Hukum | Jurnal, kajian, analisis hukum |
| Putusan / Yurisprudensi | Putusan MK, MA, PT, PN |
| Nota Kesepahaman / MoU | MoU antar instansi |
| Naskah Akademik | Naskah akademik RUU, DPRD |

## 🛠 Tech Stack

| Layer | Teknologi |
|---|---|
| Frontend | Next.js 16, TypeScript, Tailwind CSS 4 |
| Backend | FastAPI (Python), SQLAlchemy |
| Database | PostgreSQL 16 (Full-Text Search) |
| Auth | JWT, bcrypt |
| Storage | Cloudflare R2 / Local |
| Notifikasi | Fonnte API (WhatsApp) |
| Deploy | Systemd, Apache reverse proxy |

## 📁 Struktur Project

```
jdih-kepri/
├── docs/
│   └── studi-analisis.md    # Analisis lengkap
├── backend/
│   ├── alembic/
│   ├── api/
│   │   ├── routers/
│   │   └── main.py
│   ├── core/
│   │   └── models/
│   ├── services/
│   └── requirements.txt
├── frontend/
│   ├── src/
│   │   ├── app/
│   │   ├── components/
│   │   └── features/
│   └── package.json
└── README.md
```

## 🔌 API Endpoints

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/documents` | List dokumen (search, filter, pagination) |
| GET | `/api/documents/{id}` | Detail dokumen |
| POST | `/api/documents` | Tambah dokumen (admin) |
| GET | `/api/documents/themes` | Telusur tematik |
| GET | `/api/search` | Full-text search |
| GET | `/api/search/suggest` | Auto-suggest |
| POST | `/api/consultations` | Konsultasi hukum |
| GET | `/api/dashboard/stats` | Statistik |
| GET | `/api/reports/export` | Export PDF/Excel |

## 📄 Lisensi

Proprietary — All rights reserved.

---

> **JDIH Kepri** — Akses Mudah, Informasi Hukum Pasti. ⚖️
