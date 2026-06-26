# 📊 Entity Relationship Diagram (ERD) — JDIH Kepri

> **Last Updated:** 19 Juni 2026
> **Database:** MySQL 8.x (Laravel 13)
> **Conventions:** ULID primary key, `timestamps()` (created_at, updated_at), `softDeletes()` where noted

---

## Daftar Isi

1. [Overview](#1-overview)
2. [Existing Tables (Laravel Default + Spatie)](#2-existing-tables)
3. [JDIH Core Tables](#3-jdih-core-tables)
4. [JDIH Public Service Tables](#4-jdih-public-service-tables)
5. [JDIH Analytics & System Tables](#5-jdih-analytics--system-tables)
6. [Full ERD Diagram](#6-full-erd-diagram)
7. [Relationship Summary](#7-relationship-summary)
8. [Index Strategy](#8-index-strategy)

---

## 1. Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    JDIH KEPRI — DATABASE SCHEMA                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────┐    ┌──────────────┐    ┌──────────────────┐   │
│  │  AUTH & RBAC │    │  JDIH CORE   │    │  PUBLIC SERVICE  │   │
│  │  (6 tables)  │    │ (10 tables)  │    │   (5 tables)     │   │
│  └──────┬──────┘    └──────┬───────┘    └────────┬─────────┘   │
│         │                  │                      │              │
│         └──────────────────┼──────────────────────┘              │
│                            │                                     │
│                    ┌───────┴────────┐                            │
│                    │ ANALYTICS &    │                            │
│                    │ SYSTEM (5 tbl) │                            │
│                    └────────────────┘                            │
│                                                                  │
│  Total: 26 tables (11 existing + 15 new)                        │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. Existing Tables (Sudah Ada)

### 2.1 users

> Tabel utama autentikasi. Diperluas untuk JDIH dengan field `phone` dan `unit`.

```
┌─────────────────────────────────────────────┐
│                    users                     │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ name            VARCHAR(255)  NOT NULL       │
│ email           VARCHAR(255)  UNIQUE, NOT NULL│
│ email_verified_at TIMESTAMP   NULLABLE       │
│ password        VARCHAR(255)  NOT NULL       │
│ phone           VARCHAR(20)   NULLABLE  ← NEW│
│ unit            VARCHAR(100)  NULLABLE  ← NEW│
│ is_active       BOOLEAN       DEFAULT TRUE   │
│ remember_token  VARCHAR(100)  NULLABLE       │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ INDEX: email (unique)                        │
│ RELATION: hasMany login_logs                 │
│ RELATION: hasMany documents (as creator)     │
│ RELATION: hasMany consultations              │
│ RELATION: belongsToMany roles (spatie)       │
│ RELATION: belongsToMany permissions (spatie) │
└─────────────────────────────────────────────┘
```

**Migration:** `0001_01_01_000000_create_users_table.php`
**Changes needed:** Add `phone`, `unit` columns via new migration.

---

### 2.2 roles (Spatie)

```
┌─────────────────────────────────────────────┐
│                    roles                     │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ name            VARCHAR(255)  NOT NULL       │
│ guard_name      VARCHAR(255)  NOT NULL       │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ UNIQUE: [name, guard_name]                   │
│ SEED: super-admin, admin, operator, viewer   │
└─────────────────────────────────────────────┘
```

**Migration:** `2026_03_23_162752_create_permission_tables.php`

---

### 2.3 permissions (Spatie)

```
┌─────────────────────────────────────────────┐
│                 permissions                  │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ name            VARCHAR(255)  NOT NULL       │
│ guard_name      VARCHAR(255)  NOT NULL       │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ UNIQUE: [name, guard_name]                   │
│ SEED: document.*, category.*, theme.*, etc.  │
└─────────────────────────────────────────────┘
```

---

### 2.4 Pivot Tables (Spatie)

```
model_has_roles          → user_id, role_id, model_type
model_has_permissions    → permission_id, user_id, model_type
role_has_permissions     → permission_id, role_id
```

---

### 2.5 menus

```
┌─────────────────────────────────────────────┐
│                    menus                     │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ nama_menu       VARCHAR(255)  NOT NULL       │
│ nama_fitur      VARCHAR(255)  NULLABLE       │
│ alamat_url      VARCHAR(255)  NULLABLE       │
│ route_name      VARCHAR(255)  NULLABLE       │
│ ikon            VARCHAR(255)  NULLABLE       │
│ tingkatan_menu  ENUM('parent','child')       │
│ urutan          INT           DEFAULT 0      │
│ menu_induk_id   BIGINT        FK → menus.id  │
│ permission_name VARCHAR(255)  NULLABLE       │
│ tag             VARCHAR(255)  NULLABLE       │
│ is_active       BOOLEAN       DEFAULT TRUE   │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ INDEX: [menu_induk_id, urutan]               │
│ INDEX: permission_name                       │
│ INDEX: route_name                            │
│ RELATION: belongsTo menus (self, parent)     │
│ RELATION: hasMany menus (self, children)     │
└─────────────────────────────────────────────┘
```

---

### 2.6 login_logs

```
┌─────────────────────────────────────────────┐
│                 login_logs                   │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ user_id         BIGINT        FK → users.id  │
│ email           VARCHAR(255)  NOT NULL       │
│ ip_address      VARCHAR(45)   NULLABLE       │
│ user_agent      TEXT          NULLABLE       │
│ status          VARCHAR(20)   NOT NULL       │
│ created_at      TIMESTAMP    NULLABLE        │
├─────────────────────────────────────────────┤
│ RELATION: belongsTo users                    │
└─────────────────────────────────────────────┘
```

---

## 3. JDIH Core Tables

### 3.1 document_types

> Jenis dokumen hukum (Perda, Perkada, UU, PP, SK, SE, dll)

```
┌─────────────────────────────────────────────┐
│              document_types                  │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ name            VARCHAR(100)  NOT NULL       │
│                 -- "Peraturan Daerah"        │
│ code            VARCHAR(20)   UNIQUE, NOT NULL│
│                 -- "PERDA", "PERKADA", "UU"  │
│ description     TEXT          NULLABLE       │
│ parent_id       BIGINT        FK → self      │
│                 -- untuk hierarki (UU > PP)  │
│ sort_order      INT           DEFAULT 0      │
│ is_active       BOOLEAN       DEFAULT TRUE   │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ UNIQUE: code                                 │
│ INDEX: [parent_id, sort_order]               │
│ RELATION: belongsTo self (parent)            │
│ RELATION: hasMany self (children)            │
│ RELATION: hasMany documents                  │
│                                              │
│ SEED DATA:                                   │
│  UU      → Undang-Undang                     │
│  PP      → Peraturan Pemerintah              │
│  PERPRES → Peraturan Presiden                │
│  PERDA   → Peraturan Daerah                  │
│  PERKADA → Peraturan Kepala Daerah           │
│  PERGUB  → Peraturan Gubernur               │
│  SK      → Surat Keputusan                   │
│  SE      → Surat Edaran                      │
│  INSTR   → Instruksi                         │
│  HIMBAU  → Surat Himbauan                    │
└─────────────────────────────────────────────┘
```

---

### 3.2 categories

> Kategori/subjek hukum (Pendidikan, Kesehatan, Kelautan, dll)

```
┌─────────────────────────────────────────────┐
│                 categories                   │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ name            VARCHAR(100)  NOT NULL       │
│ slug            VARCHAR(120)  UNIQUE, NOT NULL│
│ description     TEXT          NULLABLE       │
│ icon            VARCHAR(50)   NULLABLE       │
│                 -- Bootstrap Icon class      │
│ parent_id       BIGINT        FK → self      │
│ sort_order      INT           DEFAULT 0      │
│ is_active       BOOLEAN       DEFAULT TRUE   │
│ documents_count INT           DEFAULT 0  ← DENORMALIZED │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ UNIQUE: slug                                 │
│ INDEX: [parent_id, sort_order]               │
│ INDEX: slug                                  │
│ RELATION: belongsTo self (parent)            │
│ RELATION: hasMany self (children)            │
│ RELATION: hasMany documents                  │
│                                              │
│ SEED DATA:                                   │
│  Pendidikan, Kesehatan, Kelautan,            │
│  Pariwisata, Pertanian, Perdagangan,         │
│  Keuangan, Lingkungan Hidup, Tenaga Kerja,  │
│  Perhubungan, Pertanahan, Kependudukan       │
└─────────────────────────────────────────────┘
```

---

### 3.3 themes

> Tematik untuk Telusur Tematik (fitur unggulan JDIH)

```
┌─────────────────────────────────────────────┐
│                   themes                     │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ name            VARCHAR(100)  NOT NULL       │
│ slug            VARCHAR(120)  UNIQUE, NOT NULL│
│ description     TEXT          NULLABLE       │
│ icon            VARCHAR(50)   NULLABLE       │
│ color           VARCHAR(7)    NULLABLE       │
│                 -- hex color untuk UI        │
│ sort_order      INT           DEFAULT 0      │
│ is_active       BOOLEAN       DEFAULT TRUE   │
│ documents_count INT           DEFAULT 0  ← DENORMALIZED │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ UNIQUE: slug                                 │
│ INDEX: slug                                  │
│ RELATION: belongsToMany documents (pivot)    │
│                                              │
│ SEED DATA:                                   │
│  Otonomi Daerah, Desentralisasi,             │
│  Pelayanan Publik, Anti Korupsi,             │
│  HAM & Gender, Kemiskinan,                   │
│  Perizinan, Pengadaan Barang/Jasa            │
└─────────────────────────────────────────────┘
```

---

### 3.4 documents

> **TABEL INTI** — Semua dokumen hukum JDIH Kepri

```
┌──────────────────────────────────────────────────────────────┐
│                       documents                               │
├──────────────────────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC                   │
│ type_id         BIGINT        FK → document_types.id NOT NULL│
│ category_id     BIGINT        FK → categories.id    NULLABLE │
│ title           TEXT          NOT NULL                        │
│                 -- judul lengkap peraturan                    │
│ number          VARCHAR(50)   NOT NULL                        │
│                 -- "12" (nomor peraturan)                     │
│ year            YEAR          NOT NULL                        │
│                 -- 2026                                       │
│ slug            VARCHAR(255)  UNIQUE, NOT NULL                │
│                 -- otomatis dari title+number+year            │
│ status          ENUM          NOT NULL, DEFAULT 'berlaku'     │
│                 -- 'berlaku','dicabut','tidak_berlaku'        │
│ teu             VARCHAR(255)  NOT NULL                        │
│                 -- Tajuk Entri Utama (BPHN standard)          │
│ subject         VARCHAR(255)  NULLABLE                        │
│                 -- subjek hukum                               │
│ source          VARCHAR(255)  NULLABLE                        │
│                 -- sumber: DPRD, Gubernur, dll                │
│ signatory       VARCHAR(255)  NULLABLE                        │
│                 -- penandatangan                              │
│ place           VARCHAR(100)  NULLABLE                        │
│                 -- tempat terbit: Tanjungpinang               │
│ date_set        DATE          NULLABLE                        │
│                 -- tanggal penetapan                          │
│ date_publish    DATE          NULLABLE                        │
│                 -- tanggal pengundangan                       │
│ date_effective  DATE          NULLABLE                        │
│                 -- tanggal berlaku                             │
│ abstract        TEXT          NULLABLE                        │
│                 -- abstrak/ringkasan                          │
│ full_text       LONGTEXT      NULLABLE                        │
│                 -- full text untuk search                     │
│ language        VARCHAR(20)   DEFAULT 'id'                    │
│                 -- bahasa dokumen                             │
│ views_count     INT UNSIGNED  DEFAULT 0  ← COUNTER           │
│ downloads_count INT UNSIGNED  DEFAULT 0  ← COUNTER           │
│ is_featured     BOOLEAN       DEFAULT FALSE                   │
│ published_at    TIMESTAMP     NULLABLE                        │
│ created_by      BIGINT        FK → users.id          NULLABLE │
│ updated_by      BIGINT        FK → users.id          NULLABLE │
│ created_at      TIMESTAMP                                     │
│ updated_at      TIMESTAMP                                     │
│ deleted_at      TIMESTAMP     NULLABLE  ← SOFT DELETE         │
├──────────────────────────────────────────────────────────────┤
│ UNIQUE: slug                                                  │
│ INDEX: [type_id, year]                                        │
│ INDEX: [category_id, status]                                  │
│ INDEX: [year, status]                                         │
│ INDEX: [status, published_at]                                 │
│ INDEX: full_text (FULLTEXT)                                   │
│ INDEX: title (FULLTEXT)                                       │
│                                                              │
│ RELATION: belongsTo document_types (type)                     │
│ RELATION: belongsTo categories (category)                     │
│ RELATION: belongsTo users (creator → created_by)              │
│ RELATION: belongsTo users (editor → updated_by)               │
│ RELATION: hasMany attachments                                 │
│ RELATION: belongsToMany themes (pivot: document_themes)       │
│ RELATION: hasMany document_relations (as source)              │
│ RELATION: hasMany document_relations (as related)             │
│ RELATION: morphMany comments                                  │
│ RELATION: morphMany audit_logs                                │
└──────────────────────────────────────────────────────────────┘
```

---

### 3.5 attachments

> File lampiran dokumen (PDF, DOC, gambar)

```
┌─────────────────────────────────────────────┐
│                attachments                   │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ document_id     BIGINT        FK → documents.id NOT NULL│
│ filename        VARCHAR(255)  NOT NULL       │
│ original_name   VARCHAR(255)  NOT NULL       │
│ file_url        VARCHAR(500)  NOT NULL       │
│ file_path       VARCHAR(500)  NOT NULL       │
│ file_size       BIGINT UNSIGNED NOT NULL     │
│                 -- dalam bytes              │
│ mime_type       VARCHAR(100)  NOT NULL       │
│                 -- application/pdf, dll     │
│ sort_order      INT           DEFAULT 0      │
│ download_count  INT UNSIGNED  DEFAULT 0      │
│ created_by      BIGINT        FK → users.id  │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ INDEX: [document_id, sort_order]             │
│ RELATION: belongsTo documents                │
│ RELATION: belongsTo users (creator)          │
└─────────────────────────────────────────────┘
```

---

### 3.6 document_themes (Pivot)

> Relasi many-to-many antara dokumen dan tema

```
┌─────────────────────────────────────────────┐
│             document_themes                  │
├─────────────────────────────────────────────┤
│ document_id     BIGINT        FK → documents.id │
│ theme_id        BIGINT        FK → themes.id    │
│ created_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ PK: [document_id, theme_id]                  │
│ RELATION: belongsTo documents                │
│ RELATION: belongsTo themes                   │
└─────────────────────────────────────────────┘
```

---

### 3.7 document_relations

> Dokumen terkait (related documents)

```
┌─────────────────────────────────────────────┐
│            document_relations                │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ source_id       BIGINT        FK → documents.id NOT NULL│
│ related_id      BIGINT        FK → documents.id NOT NULL│
│ relation_type   VARCHAR(50)   DEFAULT 'terkait'│
│                 -- 'mencabut','mengubah',   │
│                 -- 'diubah_oleh','terkait'  │
│ created_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ UNIQUE: [source_id, related_id, relation_type]│
│ INDEX: source_id                             │
│ INDEX: related_id                            │
│ RELATION: belongsTo documents (source)       │
│ RELATION: belongsTo documents (related)      │
└─────────────────────────────────────────────┘
```

---

## 4. JDIH Public Service Tables

### 4.1 consultations

> Konsultasi Hukum Online

```
┌─────────────────────────────────────────────┐
│              consultations                   │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ user_id         BIGINT        FK → users.id  │
│                 -- NULL jika anonymous       │
│ name            VARCHAR(100)  NOT NULL       │
│ email           VARCHAR(255)  NULLABLE       │
│ phone           VARCHAR(20)   NULLABLE       │
│ subject         VARCHAR(255)  NOT NULL       │
│ question        TEXT          NOT NULL       │
│ answer          TEXT          NULLABLE       │
│ answered_by     BIGINT        FK → users.id  │
│ answered_at     TIMESTAMP     NULLABLE       │
│ status          ENUM          DEFAULT 'pending'│
│                 -- 'pending','answered','closed'│
│ is_public       BOOLEAN       DEFAULT FALSE  │
│                 -- tampilkan di FAQ?        │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ INDEX: [status, created_at]                  │
│ INDEX: user_id                               │
│ RELATION: belongsTo users (questioner)       │
│ RELATION: belongsTo users (answerer)         │
└─────────────────────────────────────────────┘
```

---

### 4.2 public_hearings

> E-Public Hearing (Partisipasi Publik dalam Pembentukan Perda)

```
┌─────────────────────────────────────────────┐
│             public_hearings                  │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ title           VARCHAR(255)  NOT NULL       │
│ description     TEXT          NOT NULL       │
│ document_draft  VARCHAR(500)  NULLABLE       │
│                 -- URL draft rancangan      │
│ start_date      DATE          NOT NULL       │
│ end_date        DATE          NOT NULL       │
│ status          ENUM          DEFAULT 'open' │
│                 -- 'open','closed','archived'│
│ location        VARCHAR(255)  NULLABLE       │
│                 -- lokasi offline           │
│ online_link     VARCHAR(500)  NULLABLE       │
│                 -- link zoom/meet           │
│ created_by      BIGINT        FK → users.id  │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ INDEX: [status, start_date]                  │
│ RELATION: belongsTo users (creator)          │
│ RELATION: hasMany hearing_submissions        │
└─────────────────────────────────────────────┘
```

---

### 4.3 hearing_submissions

> Masukan dari masyarakat pada Public Hearing

```
┌─────────────────────────────────────────────┐
│           hearing_submissions                │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ hearing_id      BIGINT        FK → public_hearings.id NOT NULL│
│ user_id         BIGINT        FK → users.id  │
│                 -- NULL jika anonymous       │
│ name            VARCHAR(100)  NOT NULL       │
│ email           VARCHAR(255)  NULLABLE       │
│ institution     VARCHAR(150)  NULLABLE       │
│                 -- instansi/asal            │
│ opinion         TEXT          NOT NULL       │
│                 -- pendapat/masukan         │
│ attachment_url  VARCHAR(500)  NULLABLE       │
│                 -- file pendukung           │
│ status          ENUM          DEFAULT 'pending'│
│                 -- 'pending','reviewed','accepted','rejected'│
│ admin_note      TEXT          NULLABLE       │
│ reviewed_by     BIGINT        FK → users.id  │
│ reviewed_at     TIMESTAMP     NULLABLE       │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ INDEX: [hearing_id, status]                  │
│ RELATION: belongsTo public_hearings          │
│ RELATION: belongsTo users (submitter)        │
│ RELATION: belongsTo users (reviewer)         │
└─────────────────────────────────────────────┘
```

---

### 4.4 information_requests

> Permintaan Informasi Hukum dari Publik (PP 61/2010)

```
┌─────────────────────────────────────────────┐
│           information_requests               │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ register_number VARCHAR(50)   UNIQUE         │
│                 -- nomor register otomatis   │
│ user_id         BIGINT        FK → users.id  │
│ name            VARCHAR(100)  NOT NULL       │
│ email           VARCHAR(255)  NOT NULL       │
│ phone           VARCHAR(20)   NULLABLE       │
│ institution     VARCHAR(150)  NULLABLE       │
│ request_type    ENUM          NOT NULL       │
│                 -- 'informasi','keberatan'   │
│ subject         VARCHAR(255)  NOT NULL       │
│ description     TEXT          NOT NULL       │
│ response        TEXT          NULLABLE       │
│ responded_by    BIGINT        FK → users.id  │
│ responded_at    TIMESTAMP     NULLABLE       │
│ status          ENUM          DEFAULT 'pending'│
│                 -- 'pending','processing',   │
│                 -- 'fulfilled','rejected'    │
│ due_date        DATE          NULLABLE       │
│                 -- batas waktu (10 hari kerja)│
│ attachment_url  VARCHAR(500)  NULLABLE       │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ UNIQUE: register_number                      │
│ INDEX: [status, due_date]                    │
│ INDEX: user_id                               │
│ RELATION: belongsTo users (requester)        │
│ RELATION: belongsTo users (responder)        │
└─────────────────────────────────────────────┘
```

---

### 4.5 subscriptions

> Langganan notifikasi peraturan baru

```
┌─────────────────────────────────────────────┐
│               subscriptions                  │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ user_id         BIGINT        FK → users.id  │
│ email           VARCHAR(255)  NOT NULL       │
│ category_id     BIGINT        FK → categories.id NULLABLE│
│                 -- NULL = semua kategori    │
│ type_id         BIGINT        FK → document_types.id NULLABLE│
│                 -- NULL = semua jenis       │
│ channel         ENUM          DEFAULT 'email'│
│                 -- 'email','whatsapp','both'│
│ is_active       BOOLEAN       DEFAULT TRUE   │
│ token           VARCHAR(100)  UNIQUE         │
│                 -- untuk unsubscribe link    │
│ last_notified_at TIMESTAMP    NULLABLE       │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ UNIQUE: token                                │
│ INDEX: [is_active, category_id]              │
│ RELATION: belongsTo users                    │
│ RELATION: belongsTo categories               │
│ RELATION: belongsTo document_types           │
└─────────────────────────────────────────────┘
```

---

## 5. JDIH Analytics & System Tables

### 5.1 document_views

> Log view dokumen (untuk statistik)

```
┌─────────────────────────────────────────────┐
│             document_views                   │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ document_id     BIGINT        FK → documents.id NOT NULL│
│ user_id         BIGINT        FK → users.id  │
│                 -- NULL jika anonymous       │
│ ip_address      VARCHAR(45)   NULLABLE       │
│ user_agent      TEXT          NULLABLE       │
│ referrer        VARCHAR(500)  NULLABLE       │
│ viewed_at       TIMESTAMP     DEFAULT NOW    │
├─────────────────────────────────────────────┤
│ INDEX: [document_id, viewed_at]              │
│ INDEX: viewed_at                             │
│ RELATION: belongsTo documents                │
│ RELATION: belongsTo users                    │
└─────────────────────────────────────────────┘
```

---

### 5.2 document_downloads

> Log download lampiran

```
┌─────────────────────────────────────────────┐
│            document_downloads                │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ document_id     BIGINT        FK → documents.id NOT NULL│
│ attachment_id   BIGINT        FK → attachments.id NOT NULL│
│ user_id         BIGINT        FK → users.id  │
│ ip_address      VARCHAR(45)   NULLABLE       │
│ downloaded_at   TIMESTAMP     DEFAULT NOW    │
├─────────────────────────────────────────────┤
│ INDEX: [document_id, downloaded_at]          │
│ RELATION: belongsTo documents                │
│ RELATION: belongsTo attachments              │
│ RELATION: belongsTo users                    │
└─────────────────────────────────────────────┘
```

---

### 5.3 feedbacks

> Umpan balik & rating dari pengunjung

```
┌─────────────────────────────────────────────┐
│                 feedbacks                    │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ user_id         BIGINT        FK → users.id  │
│ name            VARCHAR(100)  NULLABLE       │
│ email           VARCHAR(255)  NULLABLE       │
│ type            ENUM          NOT NULL       │
│                 -- 'saran','masalah','pujian'│
│ subject         VARCHAR(255)  NOT NULL       │
│ message         TEXT          NOT NULL       │
│ rating          TINYINT       NULLABLE       │
│                 -- 1-5 bintang              │
│ page_url        VARCHAR(500)  NULLABLE       │
│                 -- halaman saat kirim       │
│ status          ENUM          DEFAULT 'new'  │
│                 -- 'new','read','resolved'  │
│ admin_reply     TEXT          NULLABLE       │
│ replied_by      BIGINT        FK → users.id  │
│ replied_at      TIMESTAMP     NULLABLE       │
│ created_at      TIMESTAMP                    │
│ updated_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ INDEX: [status, created_at]                  │
│ RELATION: belongsTo users                    │
└─────────────────────────────────────────────┘
```

---

### 5.4 search_logs

> Log pencarian (untuk analytics & auto-suggest)

```
┌─────────────────────────────────────────────┐
│               search_logs                    │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ query           VARCHAR(255)  NOT NULL       │
│ results_count   INT           DEFAULT 0      │
│ user_id         BIGINT        FK → users.id  │
│ ip_address      VARCHAR(45)   NULLABLE       │
│ filters         JSON          NULLABLE       │
│                 -- filter yang dipakai      │
│ searched_at     TIMESTAMP     DEFAULT NOW    │
├─────────────────────────────────────────────┤
│ INDEX: query                                 │
│ INDEX: searched_at                           │
│ RELATION: belongsTo users                    │
└─────────────────────────────────────────────┘
```

---

### 5.5 audit_logs

> Audit trail semua aktivitas admin

```
┌─────────────────────────────────────────────┐
│               audit_logs                     │
├─────────────────────────────────────────────┤
│ id              BIGINT        PK, AUTO_INC  │
│ user_id         BIGINT        FK → users.id  │
│ action          VARCHAR(50)   NOT NULL       │
│                 -- 'create','update','delete'│
│ auditable_type  VARCHAR(100)  NOT NULL       │
│                 -- 'Document','Category',dll │
│ auditable_id    BIGINT        NOT NULL       │
│ old_values      JSON          NULLABLE       │
│ new_values      JSON          NULLABLE       │
│ ip_address      VARCHAR(45)   NULLABLE       │
│ user_agent      TEXT          NULLABLE       │
│ created_at      TIMESTAMP                    │
├─────────────────────────────────────────────┤
│ INDEX: [auditable_type, auditable_id]        │
│ INDEX: user_id                               │
│ INDEX: created_at                            │
│ RELATION: belongsTo users                    │
│ RELATION: morphTo (auditable)                │
└─────────────────────────────────────────────┘
```

---

## 6. Full ERD Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                              JDIH KEPRI — FULL ERD                                          │
└─────────────────────────────────────────────────────────────────────────────────────────────┘

  ┌──────────────┐       ┌──────────────────┐       ┌──────────────────┐
  │    users     │       │  document_types  │       │   categories     │
  │──────────────│       │──────────────────│       │──────────────────│
  │ id (PK)      │       │ id (PK)          │       │ id (PK)          │
  │ name         │       │ name             │       │ name             │
  │ email        │       │ code             │       │ slug             │
  │ phone    ←NEW│       │ parent_id (FK)───┼──┐    │ parent_id (FK)───┼──┐
  │ unit     ←NEW│       └────────┬─────────┘  │    └────────┬─────────┘  │
  │ is_active    │                │            │             │            │
  └──────┬───────┘                │            └─────────────┘            │
         │                        │            (self-referencing)         │
         │ ┌──────────────────────┼────────────────────────────────────┐ │
         │ │                      ▼                                    │ │
         │ │  ┌────────────────────────────────────────────────────┐  │ │
         │ │  │                   documents                        │  │ │
         │ │  │────────────────────────────────────────────────────│  │ │
         │ │  │ id (PK)                                            │  │ │
         │ │  │ type_id (FK) ──────→ document_types.id             │  │ │
         │ │  │ category_id (FK) ──→ categories.id                 │  │ │
         │ │  │ created_by (FK) ───→ users.id                      │  │ │
         │ │  │ updated_by (FK) ───→ users.id                      │  │ │
         │ │  │ title, number, year, slug, status                  │  │ │
         │ │  │ teu, subject, source, signatory, place             │  │ │
         │ │  │ date_set, date_publish, date_effective             │  │ │
         │ │  │ abstract, full_text (FULLTEXT INDEX)               │  │ │
         │ │  │ views_count, downloads_count                       │  │ │
         │ │  │ is_featured, published_at                          │  │ │
         │ │  └───┬──────────┬──────────┬──────────┬───────────────┘  │ │
         │ │      │          │          │          │                  │ │
         │ │      ▼          ▼          ▼          ▼                  │ │
         │ │ ┌─────────┐ ┌────────┐ ┌────────┐ ┌─────────────────┐  │ │
         │ │ │attachmnt│ │doc_    │ │doc_    │ │document_        │  │ │
         │ │ │─────────│ │themes  │ │relation│ │views            │  │ │
         │ │ │id (PK)  │ │(pivot) │ │────────│ │─────────────────│  │ │
         │ │ │doc_id FK│ │doc_id  │ │id (PK) │ │id (PK)          │  │ │
         │ │ │filename │ │theme_id│ │source_id│ │doc_id FK        │  │ │
         │ │ │file_url │ └───┬────┘ │related_│ │user_id FK       │  │ │
         │ │ │file_size│     │      │id (FK) │ │ip_address       │  │ │
         │ │ │mime_type│     ▼      │relation│ │viewed_at        │  │ │
         │ │ └─────────┘ ┌────────┐ │type    │ └─────────────────┘  │ │
         │ │             │ themes │ └────────┘                       │ │
         │ │             │────────│                                  │ │
         │ │             │id (PK) │    ┌───────────────────┐        │ │
         │ │             │name    │    │document_downloads │        │ │
         │ │             │slug    │    │───────────────────│        │ │
         │ │             │color   │    │id (PK)            │        │ │
         │ │             └────────┘    │doc_id FK          │        │ │
         │ │                           │attach_id FK       │        │ │
         │ │                           │user_id FK         │        │ │
         │ │                           └───────────────────┘        │ │
         │ └────────────────────────────────────────────────────────┘ │
         │                                                            │
         │  ┌─────────────────────────────────────────────────────┐   │
         │  │              PUBLIC SERVICE TABLES                   │   │
         │  └─────────────────────────────────────────────────────┘   │
         │                                                            │
         ▼                                                            ▼
  ┌─────────────────┐  ┌─────────────────┐  ┌──────────────────────┐
  │  consultations  │  │ public_hearings  │  │ information_requests │
  │─────────────────│  │─────────────────│  │──────────────────────│
  │ id (PK)         │  │ id (PK)         │  │ id (PK)              │
  │ user_id (FK) ───┼──│ title           │  │ register_number      │
  │ name            │  │ description     │  │ user_id (FK) ────────┼──┐
  │ question        │  │ start_date      │  │ request_type         │  │
  │ answer          │  │ end_date        │  │ subject              │  │
  │ answered_by(FK)─┼──│ status          │  │ description          │  │
  │ status          │  │ created_by (FK)─┼──│ response             │  │
  │ is_public       │  └────────┬────────┘  │ responded_by (FK) ───┼──┤
  └─────────────────┘           │           │ status               │  │
         │                      ▼           │ due_date             │  │
         │           ┌────────────────────┐  └──────────────────────┘  │
         │           │hearing_submissions │         │                   │
         │           │────────────────────│         │                   │
         │           │ id (PK)            │         │                   │
         │           │ hearing_id (FK)    │         │                   │
         │           │ user_id (FK)       │         │                   │
         │           │ opinion            │         │                   │
         │           │ status             │         │                   │
         │           └────────────────────┘         │                   │
         │                                          │                   │
         │  ┌─────────────────┐   ┌─────────────────┴───────────────┐  │
         │  │ subscriptions   │   │        SYSTEM TABLES             │  │
         │  │─────────────────│   └─────────────────────────────────┘  │
         │  │ id (PK)         │                                        │
         │  │ user_id (FK) ───┤   ┌─────────────┐  ┌──────────────┐  │
         │  │ email           │   │ search_logs  │  │  audit_logs  │  │
         │  │ category_id(FK) │   │─────────────│  │──────────────│  │
         │  │ type_id (FK)    │   │ id (PK)     │  │ id (PK)      │  │
         │  │ channel         │   │ query       │  │ user_id (FK) │  │
         │  │ is_active       │   │ results_cnt │  │ action       │  │
         │  │ token           │   │ user_id(FK) │  │ auditable_*  │  │
         │  └─────────────────┘   │ searched_at │  │ old/new_vals │  │
         │                        └─────────────┘  └──────────────┘  │
         │                                                             │
         │  ┌───────────────────────────────────────────────────────┐  │
         │  │                   feedbacks                           │  │
         │  │───────────────────────────────────────────────────────│  │
         │  │ id (PK) · user_id (FK) · type · rating · message     │  │
         │  │ status · admin_reply · replied_by (FK)                │  │
         │  └───────────────────────────────────────────────────────┘  │
         │                                                             │
         │  ┌───────────────────────────────────────────────────────┐  │
         │  │                  RBAC (SPATIE)                        │  │
         │  │───────────────────────────────────────────────────────│  │
         │  │ roles ◄──► role_has_permissions ◄──► permissions      │  │
         │  │    ▲                                    ▲             │  │
         │  │    │ model_has_roles    model_has_permissions │       │  │
         │  │    └──────────┐              ┌───────────────┘       │  │
         │  │               ▼              ▼                        │  │
         │  │            users ◄──► (pivot tables)                 │  │
         │  └───────────────────────────────────────────────────────┘  │
         │                                                             │
         │  ┌───────────────────────────────────────────────────────┐  │
         │  │  menus (self-ref) · login_logs · sessions · cache     │  │
         │  └───────────────────────────────────────────────────────┘  │
         └─────────────────────────────────────────────────────────────┘
```

---

## 7. Relationship Summary

### One-to-Many (hasMany / belongsTo)
| Parent | Child | FK Column | Keterangan |
|---|---|---|---|
| `users` | `documents` | `created_by` | User membuat dokumen |
| `users` | `documents` | `updated_by` | User mengubah dokumen |
| `users` | `login_logs` | `user_id` | Log login |
| `users` | `consultations` | `user_id` | Pertanyaan konsultasi |
| `users` | `consultations` | `answered_by` | Jawaban konsultasi |
| `users` | `public_hearings` | `created_by` | Membuat hearing |
| `users` | `hearing_submissions` | `user_id` | Mengirim masukan |
| `users` | `information_requests` | `user_id` | Meminta informasi |
| `users` | `subscriptions` | `user_id` | Langganan notifikasi |
| `users` | `feedbacks` | `user_id` | Mengirim feedback |
| `users` | `audit_logs` | `user_id` | Aktivitas admin |
| `document_types` | `documents` | `type_id` | Jenis dokumen |
| `categories` | `documents` | `category_id` | Kategori dokumen |
| `documents` | `attachments` | `document_id` | File lampiran |
| `documents` | `document_views` | `document_id` | View log |
| `documents` | `document_downloads` | `document_id` | Download log |
| `documents` | `document_relations` | `source_id` | Relasi dokumen |
| `documents` | `document_relations` | `related_id` | Dokumen terkait |
| `public_hearings` | `hearing_submissions` | `hearing_id` | Masukan hearing |
| `attachments` | `document_downloads` | `attachment_id` | Download lampiran |

### Self-Referencing
| Table | FK Column | Keterangan |
|---|---|---|
| `document_types` | `parent_id` | Hierarki jenis (UU > PP > dll) |
| `categories` | `parent_id` | Sub-kategori |
| `menus` | `menu_induk_id` | Parent-child sidebar |

### Many-to-Many (Pivot)
| Table A | Pivot | Table B | Keterangan |
|---|---|---|---|
| `documents` | `document_themes` | `themes` | Dokumen ↔ Tematik |
| `users` | `model_has_roles` | `roles` | User ↔ Role (Spatie) |
| `roles` | `role_has_permissions` | `permissions` | Role ↔ Permission |
| `users` | `model_has_permissions` | `permissions` | User ↔ Permission |

### Polymorphic
| Table | Columns | Target |
|---|---|---|
| `audit_logs` | `auditable_type`, `auditable_id` | Any model (Document, Category, dll) |

---

## 8. Index Strategy

### Full-Text Search (MySQL FULLTEXT)
```sql
-- documents table
ALTER TABLE documents ADD FULLTEXT INDEX ft_title (title);
ALTER TABLE documents ADD FULLTEXT INDEX ft_full_text (full_text);
ALTER TABLE documents ADD FULLTEXT INDEX ft_search (title, abstract, full_text);
```

### Composite Indexes (Performance)
```sql
-- Pencarian umum publik
documents: [status, published_at]     → listing publik
documents: [type_id, year]            → filter jenis + tahun
documents: [category_id, status]      → filter kategori
documents: [year, status]             → filter tahun

-- Analytics
document_views: [document_id, viewed_at]      → statistik per dokumen
document_downloads: [document_id, downloaded_at] → statistik download
search_logs: [query]                          → popular search
audit_logs: [auditable_type, auditable_id]    → history per objek

-- Public service
consultations: [status, created_at]   → antrian pertanyaan
information_requests: [status, due_date] → deadline tracking
hearing_submissions: [hearing_id, status] → rekap masukan
```

### Denormalized Counters
```
documents.views_count      → increment on view (avoid COUNT query)
documents.downloads_count  → increment on download
categories.documents_count → increment on document create/delete
themes.documents_count     → increment on document_themes change
```

---

## 📊 Table Count Summary

| Group | Tables | Status |
|---|---|---|
| **Laravel Default** | users, sessions, password_reset_tokens, cache, cache_locks, jobs, job_batches, failed_jobs | ✅ Existing |
| **Spatie RBAC** | permissions, roles, model_has_permissions, model_has_roles, role_has_permissions | ✅ Existing |
| **Admin System** | menus, login_logs | ✅ Existing |
| **JDIH Core** | document_types, categories, themes, documents, attachments, document_themes, document_relations | 🔲 New |
| **Public Service** | consultations, public_hearings, hearing_submissions, information_requests, subscriptions | 🔲 New |
| **Analytics** | document_views, document_downloads, search_logs, feedbacks, audit_logs | 🔲 New |
| **TOTAL** | **26 tables** | 11 existing + 15 new |

---

> **Catatan:** ERD ini akan di-update sesuai dengan list fitur yang dikirimkan oleh user.
> Struktur tabel dapat berubah (tambah/kolom/rename) sesuai kebutuhan implementasi.

---

*JDIH Kepri — Akses Mudah, Informasi Hukum Pasti.* ⚖️
