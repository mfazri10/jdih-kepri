# Entity Relationship Diagram (ERD)
## Sistem JDIH Kepulauan Riau

> **Last Updated:** 27 Juni 2026
> **Database:** MySQL 8.4 (Laravel 13)
> **Total Tables:** 42 (11 existing + 14 core JDIH + 8 public service + 9 system/analytics)

---

### Diagram ERD

```mermaid
erDiagram
    %% ============================================
    %% RELATIONSHIPS
    %% ============================================

    %% Core Entities
    users ||--o{ documents : creates
    users ||--o{ document_versions : creates
    users ||--o{ comments : writes
    users ||--o{ audit_logs : generates
    users ||--o{ notifications : receives
    users ||--o{ user_activities : performs
    users }|--|| roles : has
    users ||--o{ bookmarks : creates
    users ||--o{ reading_history : tracks
    users ||--o{ login_logs : logs_in

    roles ||--o{ role_permissions : has
    permissions ||--o{ role_permissions : granted_to

    %% Menu System
    menus ||--o{ menus : parent_child

    %% Document Management
    documents ||--o{ document_versions : has
    documents ||--o{ document_relations : source
    documents ||--o{ document_relations : target
    documents }|--|| document_types : belongs_to
    documents }|--|| categories : in
    documents }|--|| status : has
    documents ||--o{ document_tags : tagged_with
    documents ||--o{ document_attachments : has
    documents ||--o{ comments : discussed_in
    documents ||--o{ document_statistics : tracks
    documents ||--o{ bookmarks : bookmarked_by
    documents ||--o{ reading_history : read_by
    documents ||--o{ document_themes : themed_as

    tags ||--o{ document_tags : used_in
    themes ||--o{ document_themes : applied_to

    %% Public Service
    users ||--o{ consultations : asks
    users ||--o{ hearing_submissions : submits
    users ||--o{ information_requests : requests
    users ||--o{ subscriptions : subscribes
    users ||--o{ feedbacks : sends_feedback
    public_hearings ||--o{ hearing_submissions : receives

    %% Workflow System
    documents ||--o{ workflow_instances : follows
    workflow_templates ||--o{ workflow_instances : instantiated
    workflow_templates ||--o{ workflow_steps : contains
    workflow_instances ||--o{ workflow_step_instances : has
    workflow_steps ||--o{ workflow_step_instances : instantiated
    workflow_step_instances ||--o{ workflow_approvals : requires
    users ||--o{ workflow_approvals : approves

    %% Advanced Search
    documents ||--o{ document_search_index : indexed
    search_queries ||--o{ search_query_logs : logged
    users ||--o{ search_query_logs : performs

    %% Analytics
    documents ||--o{ document_analytics : analyzed
    users ||--o{ user_analytics : analyzed

    %% Integration
    documents ||--o{ api_requests : accessed_via
    users ||--o{ api_keys : owns

    %% ============================================
    %% ENTITIES DEFINITION
    %% ============================================

    %% ---------- USER & RBAC ----------

    users {
        bigint unsigned id PK "AUTO_INCREMENT"
        string username UK
        string email UK
        string password_hash
        string full_name
        string nip "NIP pegawai (opsional)"
        bigint unsigned role_id FK
        string phone "No. HP untuk WA notif"
        string avatar_url
        boolean is_active
        timestamp email_verified_at
        timestamp last_login_at
        json preferences "user settings"
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "soft delete"
    }

    roles {
        bigint unsigned id PK "AUTO_INCREMENT"
        string name UK "super-admin, admin, operator, viewer, public"
        string display_name
        text description
        int level "hierarchy level"
        boolean is_system "system role cannot delete"
        json permissions_cache
        timestamp created_at
        timestamp updated_at
    }

    permissions {
        bigint unsigned id PK "AUTO_INCREMENT"
        string name UK "e.g. document.create, category.delete"
        string display_name
        string module "e.g. document, category, user"
        text description
        timestamp created_at
    }

    role_permissions {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned role_id FK
        bigint unsigned permission_id FK
        timestamp granted_at
        bigint unsigned granted_by FK "admin who granted"
    }

    login_logs {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK "nullable for failed attempts"
        string email
        string ip_address
        text user_agent
        string status "success, failed, deactivated"
        timestamp created_at
    }

    menus {
        bigint unsigned id PK "AUTO_INCREMENT"
        string nama_menu
        string nama_fitur "nullable"
        string alamat_url "nullable"
        string route_name "nullable, Laravel route name"
        string ikon "Bootstrap Icon class"
        enum tingkatan_menu "parent, child"
        int urutan "sort order"
        bigint unsigned menu_induk_id FK "self-ref, parent menu"
        string permission_name "nullable, gates visibility"
        string tag "nullable"
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    %% ---------- DOCUMENT CORE ----------

    documents {
        bigint unsigned id PK "AUTO_INCREMENT"
        string doc_number UK "nomor peraturan, e.g. 12/2026"
        string title "judul lengkap peraturan"
        text abstract "ringkasan/abstrak"
        bigint unsigned type_id FK "→ document_types"
        bigint unsigned category_id FK "→ categories, nullable"
        bigint unsigned status_id FK "→ status"
        date published_date "tanggal pengundangan"
        date effective_date "tanggal berlaku"
        date established_date "tanggal penetapan"
        string issuing_institution "sumber: DPRD, Gubernur"
        string signer_name "pejabat penandatangan"
        string signer_title "jabatan penandatangan"
        string place "tempat terbit: Tanjungpinang"
        text source "sumber dokumen"
        text full_text "isi lengkap untuk FULLTEXT search"
        text notes "catatan admin"
        string language "id (Indonesia)"
        int view_count "denormalized counter"
        int download_count "denormalized counter"
        bigint unsigned created_by FK "→ users"
        bigint unsigned updated_by FK "→ users"
        bigint unsigned approved_by FK "→ users, nullable"
        timestamp approved_at
        boolean is_featured "ditampilkan di beranda"
        boolean is_archived
        json metadata "BPHN extra fields"
        timestamp published_at "waktu publish ke publik"
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "soft delete"
    }

    document_types {
        bigint unsigned id PK "AUTO_INCREMENT"
        string code UK "UU, PP, PERPRES, PERDA, PERKADA, SK, SE"
        string name "Undang-Undang, Peraturan Daerah, dll"
        text description
        string icon "Bootstrap Icon class"
        bigint unsigned parent_id FK "self-ref, hierarki: UU > PP"
        int sort_order
        boolean is_active
        json validation_rules "per-type validation"
        timestamp created_at
        timestamp updated_at
    }

    categories {
        bigint unsigned id PK "AUTO_INCREMENT"
        string code UK "e.g. PEND, KES, KEL"
        string name "Pendidikan, Kesehatan, Kelautan"
        text description
        string icon "Bootstrap Icon class"
        bigint unsigned parent_id FK "self-ref, sub-kategori"
        string path "materialized path: /1/5/12"
        int level "0=root, 1=sub, 2=sub-sub"
        int sort_order
        boolean is_active
        int documents_count "denormalized counter"
        timestamp created_at
        timestamp updated_at
    }

    status {
        bigint unsigned id PK "AUTO_INCREMENT"
        string code UK "berlaku, dicabut, tidak_berlaku"
        string name "Berlaku, Dicabut, Tidak Berlaku"
        string color "hex: #28a745, #dc3545"
        text description
        int sort_order
        boolean is_active
        timestamp created_at
    }

    themes {
        bigint unsigned id PK "AUTO_INCREMENT"
        string name "Otonomi Daerah, Anti Korupsi, HAM"
        string slug UK "otonomi-daerah, anti-korupsi"
        text description
        string icon "Bootstrap Icon class"
        string color "hex color for UI card"
        int sort_order
        boolean is_active
        int documents_count "denormalized counter"
        timestamp created_at
        timestamp updated_at
    }

    document_themes {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned document_id FK
        bigint unsigned theme_id FK
        timestamp created_at
    }

    document_versions {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned document_id FK
        int version_number "1, 2, 3..."
        string title "judul saat versi ini"
        text change_summary "apa yang berubah"
        text content "isi dokumen versi ini"
        string file_path "path file versi ini"
        string file_name
        bigint file_size "bytes"
        string mime_type "application/pdf"
        string checksum "SHA-256 for integrity"
        bigint unsigned created_by FK
        json metadata
        timestamp created_at
    }

    document_relations {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned source_document_id FK
        bigint unsigned target_document_id FK
        string relation_type "mencabut, mengubah, terkait, mengatur_tentang"
        text description "penjelasan relasi"
        bigint unsigned created_by FK
        timestamp created_at
    }

    tags {
        bigint unsigned id PK "AUTO_INCREMENT"
        string name UK "tag name"
        string slug UK "tag-slug"
        text description
        int usage_count "denormalized counter"
        timestamp created_at
        timestamp updated_at
    }

    document_tags {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned document_id FK
        bigint unsigned tag_id FK
        bigint unsigned created_by FK
        timestamp created_at
    }

    document_attachments {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned document_id FK
        string file_name "stored filename"
        string original_name "original upload name"
        string file_path "storage path"
        string file_url "public URL"
        bigint file_size "bytes"
        string mime_type "application/pdf, image/png"
        string type "utama, lampiran, pendukung"
        text description
        int sort_order
        int download_count "denormalized counter"
        bigint unsigned uploaded_by FK
        timestamp created_at
    }

    comments {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned document_id FK
        bigint unsigned parent_id FK "self-ref, threaded comments"
        bigint unsigned user_id FK
        text content
        boolean is_internal "internal admin note vs public"
        boolean is_approved
        bigint unsigned approved_by FK
        timestamp approved_at
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "soft delete"
    }

    %% ---------- WORKFLOW SYSTEM ----------

    workflow_templates {
        bigint unsigned id PK "AUTO_INCREMENT"
        string name UK "e.g. Persetujuan Perda"
        text description
        bigint unsigned document_type_id FK
        boolean is_active
        boolean is_default "default template for type"
        json config "workflow settings"
        bigint unsigned created_by FK
        timestamp created_at
        timestamp updated_at
    }

    workflow_steps {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned template_id FK
        string name "e.g. Review Legal, Approval Gubernur"
        text description
        int step_order "1, 2, 3..."
        string step_type "review, approval, notification"
        bigint unsigned role_id FK "who can do this step"
        int required_approvals "how many approvals needed"
        int timeout_hours "auto-escalate after N hours"
        json conditions "conditional logic"
        json actions "auto-actions on complete"
        timestamp created_at
        timestamp updated_at
    }

    workflow_instances {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned document_id FK
        bigint unsigned template_id FK
        string status "pending, in_progress, completed, rejected, cancelled"
        bigint unsigned current_step_id FK
        bigint unsigned initiated_by FK
        timestamp initiated_at
        timestamp completed_at
        json metadata
        timestamp created_at
        timestamp updated_at
    }

    workflow_step_instances {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned workflow_instance_id FK
        bigint unsigned workflow_step_id FK
        int step_order
        string status "pending, in_progress, completed, skipped, rejected"
        timestamp started_at
        timestamp completed_at
        timestamp deadline_at "auto-escalate deadline"
        json data "step-specific data"
        timestamp created_at
        timestamp updated_at
    }

    workflow_approvals {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned step_instance_id FK
        bigint unsigned user_id FK
        string action "approve, reject, request_revision"
        text comment "alasan approve/reject"
        json data "attachment, revision notes"
        timestamp approved_at
        timestamp created_at
    }

    %% ---------- PUBLIC SERVICE ----------

    consultations {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK "nullable if anonymous"
        string name "nama penanya"
        string email
        string phone "no. HP"
        string subject "topik pertanyaan"
        text question "isi pertanyaan"
        text answer "jawaban admin"
        bigint unsigned answered_by FK "→ users"
        timestamp answered_at
        string status "pending, answered, closed"
        boolean is_public "tampilkan di FAQ publik"
        string category "kategori pertanyaan"
        timestamp created_at
        timestamp updated_at
    }

    public_hearings {
        bigint unsigned id PK "AUTO_INCREMENT"
        string title "judul public hearing"
        text description "deskripsi lengkap"
        string document_draft_url "URL draft rancangan"
        date start_date
        date end_date
        string status "open, closed, archived"
        string location "lokasi offline"
        string online_link "link zoom/meet"
        int max_participants
        int current_participants "denormalized counter"
        bigint unsigned created_by FK "→ users"
        timestamp created_at
        timestamp updated_at
    }

    hearing_submissions {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned hearing_id FK "→ public_hearings"
        bigint unsigned user_id FK "nullable if anonymous"
        string name "nama peserta"
        string email
        string phone
        string institution "instansi/asal"
        text opinion "pendapat/masukan"
        string attachment_url "file pendukung"
        string status "pending, reviewed, accepted, rejected"
        text admin_note "catatan admin"
        bigint unsigned reviewed_by FK "→ users"
        timestamp reviewed_at
        timestamp created_at
        timestamp updated_at
    }

    information_requests {
        bigint unsigned id PK "AUTO_INCREMENT"
        string register_number UK "nomor register otomatis"
        bigint unsigned user_id FK "nullable"
        string name "nama pemohon"
        string email
        string phone
        string institution "instansi pemohon"
        string request_type "informasi, keberatan"
        string subject "perihal permintaan"
        text description "detail permintaan"
        text response "jawaban dari PPID"
        bigint unsigned responded_by FK "→ users"
        timestamp responded_at
        string status "pending, processing, fulfilled, rejected"
        date due_date "batas waktu 10 hari kerja"
        string attachment_url "file pendukung"
        timestamp created_at
        timestamp updated_at
    }

    subscriptions {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK "nullable"
        string email "email subscriber"
        string phone "no. HP untuk WA"
        bigint unsigned category_id FK "nullable, NULL = semua"
        bigint unsigned document_type_id FK "nullable, NULL = semua"
        string channel "email, whatsapp, both"
        boolean is_active
        string token UK "untuk unsubscribe link"
        timestamp last_notified_at
        timestamp created_at
        timestamp updated_at
    }

    feedbacks {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK "nullable"
        string name "nama pemberi feedback"
        string email
        string type "saran, masalah, pujian"
        string subject
        text message "isi feedback"
        int rating "1-5 bintang, nullable"
        string page_url "halaman saat kirim"
        string status "new, read, resolved"
        text admin_reply "balasan admin"
        bigint unsigned replied_by FK "→ users"
        timestamp replied_at
        timestamp created_at
        timestamp updated_at
    }

    %% ---------- SEARCH & ANALYTICS ----------

    document_statistics {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned document_id FK
        date stat_date "tanggal statistik"
        int views "views hari itu"
        int downloads "downloads hari itu"
        int shares "shares hari itu"
        int bookmarks "bookmarks hari itu"
        json details "breakdown per jam, sumber traffic"
        timestamp created_at
        timestamp updated_at
    }

    document_search_index {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned document_id FK
        text search_vector "FULLTEXT indexed: title + abstract + full_text"
        json indexed_fields "metadata yang di-index"
        timestamp indexed_at
    }

    search_queries {
        bigint unsigned id PK "AUTO_INCREMENT"
        string query_text UK "unique query text"
        int search_count "berapa kali dicari"
        timestamp last_searched_at
        timestamp created_at
    }

    search_query_logs {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK "nullable"
        bigint unsigned search_query_id FK
        string query_text "query as typed"
        json filters "filter yang dipakai"
        int results_count "jumlah hasil"
        timestamp searched_at
    }

    bookmarks {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK
        bigint unsigned document_id FK
        text notes "catatan personal user"
        timestamp created_at
    }

    reading_history {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK
        bigint unsigned document_id FK
        int duration_seconds "lama baca"
        int progress_percentage "0-100"
        timestamp read_at
    }

    user_activities {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK
        string activity_type "login, view, download, search, comment"
        string entity_type "Document, Category, dll"
        bigint unsigned entity_id "polymorphic"
        json data "activity metadata"
        string ip_address
        string user_agent
        timestamp created_at
    }

    audit_logs {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK
        string action "create, update, delete, publish, approve"
        string entity_type "Document, Category, User, dll"
        bigint unsigned entity_id "polymorphic"
        json old_values "data sebelum perubahan"
        json new_values "data sesudah perubahan"
        string ip_address
        string user_agent
        string description "human-readable: 'Membuat Perda No. 12/2026'"
        timestamp created_at
    }

    notifications {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK
        string type "document_new, deadline_reminder, approval_request"
        string title
        text message
        json data "notification payload"
        boolean is_read
        timestamp read_at
        string channel "in_app, email, whatsapp"
        string wa_message_id "Fonnte message ID, nullable"
        timestamp created_at
    }

    document_analytics {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned document_id FK
        date analytics_date
        int unique_visitors
        int total_views
        int downloads
        int avg_read_duration "detik"
        json traffic_sources "google, direct, social"
        json user_demographics "lokasi, device"
        timestamp created_at
        timestamp updated_at
    }

    user_analytics {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK
        date analytics_date
        int documents_viewed
        int documents_downloaded
        int searches_performed
        int comments_posted
        int time_spent_minutes
        json activity_breakdown
        timestamp created_at
        timestamp updated_at
    }

    %% ---------- INTEGRATION ----------

    api_keys {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned user_id FK
        string key_hash UK "hashed API key"
        string name "key name, e.g. 'Mobile App'"
        text description
        json permissions "allowed endpoints"
        timestamp last_used_at
        timestamp expires_at
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    api_requests {
        bigint unsigned id PK "AUTO_INCREMENT"
        bigint unsigned api_key_id FK
        bigint unsigned document_id FK "nullable"
        string endpoint "/api/v1/documents"
        string method "GET, POST, PUT, DELETE"
        int response_code "200, 404, 500"
        int response_time_ms "response time"
        string ip_address
        string user_agent
        timestamp created_at
    }
```

---

### Penjelasan Relasi

#### 1. User Management
- **users ↔ roles**: Many-to-One (Setiap user memiliki satu role)
- **roles ↔ permissions**: Many-to-Many melalui `role_permissions`
- **users ↔ documents**: One-to-Many (User membuat banyak dokumen)
- **users ↔ login_logs**: One-to-Many (Audit login)
- **menus → menus**: Self-referencing (parent → child sidebar)

#### 2. Document Management
- **documents ↔ document_types**: Many-to-One (Dokumen memiliki satu tipe)
- **documents ↔ categories**: Many-to-One (Dokumen dalam satu kategori)
- **documents ↔ status**: Many-to-One (Dokumen memiliki satu status)
- **documents ↔ themes**: Many-to-Many melalui `document_themes` (Telusur Tematik)
- **documents ↔ document_versions**: One-to-Many (Dokumen memiliki banyak versi)
- **documents ↔ tags**: Many-to-Many melalui `document_tags`
- **documents ↔ document_relations**: Self-referencing Many-to-Many (mencabut, mengubah, terkait)

#### 3. Public Service
- **users ↔ consultations**: One-to-Many (User bertanya hukum)
- **public_hearings ↔ hearing_submissions**: One-to-Many (Hearing menerima masukan)
- **users ↔ information_requests**: One-to-Many (Permintaan informasi publik)
- **users ↔ subscriptions**: One-to-Many (Langganan notifikasi per kategori)
- **users ↔ feedbacks**: One-to-Many (Umpan balik pengunjung)

#### 4. Workflow System
- **workflow_templates ↔ workflow_steps**: One-to-Many (Template memiliki banyak step)
- **documents ↔ workflow_instances**: One-to-Many (Dokumen memiliki banyak workflow)
- **workflow_instances ↔ workflow_step_instances**: One-to-Many
- **workflow_step_instances ↔ workflow_approvals**: One-to-Many

#### 5. Search & Analytics
- **documents ↔ document_search_index**: One-to-One (FULLTEXT search)
- **documents ↔ document_analytics**: One-to-Many (Tracking per hari)
- **users ↔ user_analytics**: One-to-Many (Tracking per hari)
- **search_queries ↔ search_query_logs**: One-to-Many (Log pencarian)

#### 6. User Interaction
- **users ↔ bookmarks ↔ documents**: Many-to-Many (Bookmark dokumen)
- **users ↔ reading_history ↔ documents**: Many-to-Many (Riwayat baca)
- **users ↔ comments ↔ documents**: Many-to-Many (Komentar dokumen)

---

### Indeks Penting

```sql
-- User indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_role_id ON users(role_id);

-- Document indexes
CREATE INDEX idx_documents_type_id ON documents(type_id);
CREATE INDEX idx_documents_category_id ON documents(category_id);
CREATE INDEX idx_documents_status_id ON documents(status_id);
CREATE INDEX idx_documents_created_by ON documents(created_by);
CREATE INDEX idx_documents_published_date ON documents(published_date);

-- FULLTEXT search (MySQL 8.4)
ALTER TABLE documents ADD FULLTEXT INDEX ft_documents_title (title);
ALTER TABLE documents ADD FULLTEXT INDEX ft_documents_fulltext (title, abstract, full_text);
ALTER TABLE document_search_index ADD FULLTEXT INDEX ft_search_vector (search_vector);

-- Version indexes
CREATE INDEX idx_document_versions_document_id ON document_versions(document_id);
CREATE INDEX idx_document_versions_version_number ON document_versions(document_id, version_number);

-- Theme pivot indexes
CREATE INDEX idx_document_themes_document_id ON document_themes(document_id);
CREATE INDEX idx_document_themes_theme_id ON document_themes(theme_id);

-- Public service indexes
CREATE INDEX idx_consultations_status ON consultations(status, created_at);
CREATE INDEX idx_consultations_user_id ON consultations(user_id);
CREATE INDEX idx_public_hearings_status ON public_hearings(status, start_date);
CREATE INDEX idx_hearing_submissions_hearing ON hearing_submissions(hearing_id, status);
CREATE INDEX idx_information_requests_status ON information_requests(status, due_date);
CREATE INDEX idx_subscriptions_active ON subscriptions(is_active, category_id);
CREATE INDEX idx_subscriptions_token ON subscriptions(token);
CREATE INDEX idx_feedbacks_status ON feedbacks(status, created_at);

-- Workflow indexes
CREATE INDEX idx_workflow_instances_document_id ON workflow_instances(document_id);
CREATE INDEX idx_workflow_instances_status ON workflow_instances(status);
CREATE INDEX idx_workflow_step_instances_workflow ON workflow_step_instances(workflow_instance_id);

-- Analytics indexes
CREATE INDEX idx_document_statistics_document_id ON document_statistics(document_id);
CREATE INDEX idx_document_statistics_stat_date ON document_statistics(stat_date);
CREATE INDEX idx_document_analytics_document_id ON document_analytics(document_id);
CREATE INDEX idx_document_analytics_date ON document_analytics(analytics_date);

-- Search indexes
CREATE INDEX idx_search_query_logs_user_id ON search_query_logs(user_id);
CREATE INDEX idx_search_query_logs_searched_at ON search_query_logs(searched_at);
CREATE INDEX idx_search_queries_count ON search_queries(search_count DESC);

-- Activity indexes
CREATE INDEX idx_user_activities_user_id ON user_activities(user_id);
CREATE INDEX idx_user_activities_created_at ON user_activities(created_at);
CREATE INDEX idx_audit_logs_user_id ON audit_logs(user_id);
CREATE INDEX idx_audit_logs_entity ON audit_logs(entity_type, entity_id);

-- Menu indexes
CREATE INDEX idx_menus_parent_order ON menus(menu_induk_id, urutan);
CREATE INDEX idx_menus_permission ON menus(permission_name);
CREATE INDEX idx_menus_route ON menus(route_name);

-- Login log indexes
CREATE INDEX idx_login_logs_user ON login_logs(user_id, created_at);
CREATE INDEX idx_login_logs_status ON login_logs(status, created_at);

-- Notification indexes
CREATE INDEX idx_notifications_user ON notifications(user_id, is_read, created_at);
CREATE INDEX idx_notifications_type ON notifications(type, created_at);

-- Bookmark & reading history
CREATE INDEX idx_bookmarks_user ON bookmarks(user_id, created_at);
CREATE INDEX idx_reading_history_user ON reading_history(user_id, read_at);
```

---

### Constraint Penting

```sql
-- Unique constraints
ALTER TABLE users ADD CONSTRAINT uk_users_email UNIQUE (email);
ALTER TABLE users ADD CONSTRAINT uk_users_username UNIQUE (username);
ALTER TABLE documents ADD CONSTRAINT uk_documents_doc_number UNIQUE (doc_number);
ALTER TABLE roles ADD CONSTRAINT uk_roles_name UNIQUE (name);
ALTER TABLE permissions ADD CONSTRAINT uk_permissions_name UNIQUE (name);
ALTER TABLE categories ADD CONSTRAINT uk_categories_code UNIQUE (code);
ALTER TABLE categories ADD CONSTRAINT uk_categories_slug UNIQUE (slug);
ALTER TABLE document_types ADD CONSTRAINT uk_document_types_code UNIQUE (code);
ALTER TABLE status ADD CONSTRAINT uk_status_code UNIQUE (code);
ALTER TABLE themes ADD CONSTRAINT uk_themes_slug UNIQUE (slug);
ALTER TABLE tags ADD CONSTRAINT uk_tags_name UNIQUE (name);
ALTER TABLE tags ADD CONSTRAINT uk_tags_slug UNIQUE (slug);
ALTER TABLE subscriptions ADD CONSTRAINT uk_subscriptions_token UNIQUE (token);
ALTER TABLE information_requests ADD CONSTRAINT uk_info_requests_register UNIQUE (register_number);
ALTER TABLE api_keys ADD CONSTRAINT uk_api_keys_hash UNIQUE (key_hash);

-- Foreign key constraints with cascading
ALTER TABLE documents
  ADD CONSTRAINT fk_documents_created_by
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL;

ALTER TABLE documents
  ADD CONSTRAINT fk_documents_type
  FOREIGN KEY (type_id) REFERENCES document_types(id) ON DELETE RESTRICT;

ALTER TABLE documents
  ADD CONSTRAINT fk_documents_category
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;

ALTER TABLE documents
  ADD CONSTRAINT fk_documents_status
  FOREIGN KEY (status_id) REFERENCES status(id) ON DELETE RESTRICT;

ALTER TABLE document_versions
  ADD CONSTRAINT fk_document_versions_document_id
  FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE;

ALTER TABLE document_themes
  ADD CONSTRAINT fk_document_themes_document
  FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE;

ALTER TABLE document_themes
  ADD CONSTRAINT fk_document_themes_theme
  FOREIGN KEY (theme_id) REFERENCES themes(id) ON DELETE CASCADE;

ALTER TABLE document_attachments
  ADD CONSTRAINT fk_attachments_document
  FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE;

ALTER TABLE workflow_instances
  ADD CONSTRAINT fk_workflow_instances_document_id
  FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE;

ALTER TABLE hearing_submissions
  ADD CONSTRAINT fk_hearing_submissions_hearing
  FOREIGN KEY (hearing_id) REFERENCES public_hearings(id) ON DELETE CASCADE;

ALTER TABLE consultations
  ADD CONSTRAINT fk_consultations_answered_by
  FOREIGN KEY (answered_by) REFERENCES users(id) ON DELETE SET NULL;

-- Check constraints
ALTER TABLE document_versions
  ADD CONSTRAINT chk_version_number_positive
  CHECK (version_number > 0);

ALTER TABLE workflow_steps
  ADD CONSTRAINT chk_step_order_positive
  CHECK (step_order > 0);

ALTER TABLE document_statistics
  ADD CONSTRAINT chk_statistics_non_negative
  CHECK (views >= 0 AND downloads >= 0);

ALTER TABLE feedbacks
  ADD CONSTRAINT chk_rating_range
  CHECK (rating IS NULL OR (rating >= 1 AND rating <= 5));

ALTER TABLE information_requests
  ADD CONSTRAINT chk_request_type
  CHECK (request_type IN ('informasi', 'keberatan'));
```

---

### Catatan Implementasi

1. **Auto-Increment INT sebagai Primary Key**: Semua tabel menggunakan `bigint unsigned` AUTO_INCREMENT (Laravel default) untuk konsistensi dan performa
2. **Soft Delete**: Tabel `documents`, `comments`, `users` menggunakan `deleted_at` untuk soft delete
3. **JSON Fields**: Menggunakan `json` (MySQL 8.4) untuk fleksibilitas data yang sering berubah
4. **Full-Text Search**: Menggunakan MySQL `FULLTEXT` index pada `title`, `abstract`, `full_text`
5. **Denormalized Counters**: `view_count`, `download_count`, `documents_count` di-update via event/listener untuk hindari COUNT query
6. **Audit Trail**: Lengkap dengan `audit_logs` (data changes) dan `user_activities` (user actions)
7. **Timestamp**: Semua tabel memiliki `created_at` dan beberapa dengan `updated_at`
8. **Self-Referencing**: `categories.parent_id`, `document_types.parent_id`, `menus.menu_induk_id`, `comments.parent_id`
9. **Polymorphic**: `audit_logs.entity_type/entity_id`, `user_activities.entity_type/entity_id`
10. **BPHN Standard**: Field `doc_number`, `published_date`, `effective_date`, `issuing_institution`, `signer_name` sesuai Permenkumham 8/2019

---

### Optimisasi

1. **Partitioning**: Pertimbangkan partisi untuk tabel besar seperti:
   - `document_analytics` (by date)
   - `user_activities` (by date)
   - `audit_logs` (by date)
   - `search_query_logs` (by date)

2. **Read Replicas**: Untuk load balancing query read-heavy
3. **Caching**: Redis untuk frequently accessed data (document list, statistics, popular searches)
4. **Queue**: Laravel Queue untuk proses berat (bulk import, notification sending, search index update)

---

### Ringkasan Tabel

| Grup | Jumlah | Tabel |
|---|---|---|
| **User & RBAC** | 6 | users, roles, permissions, role_permissions, login_logs, menus |
| **Document Core** | 10 | documents, document_types, categories, status, themes, document_themes, document_versions, document_relations, tags, document_tags |
| **Document Support** | 2 | document_attachments, comments |
| **Public Service** | 5 | consultations, public_hearings, hearing_submissions, information_requests, subscriptions |
| **Feedback** | 1 | feedbacks |
| **Workflow** | 5 | workflow_templates, workflow_steps, workflow_instances, workflow_step_instances, workflow_approvals |
| **Search** | 3 | document_search_index, search_queries, search_query_logs |
| **User Data** | 3 | bookmarks, reading_history, notifications |
| **Analytics** | 4 | document_statistics, document_analytics, user_analytics, user_activities |
| **System** | 3 | audit_logs, api_keys, api_requests |
| **TOTAL** | **42** | |

---

> **JDIH Kepri** — Akses Mudah, Informasi Hukum Pasti. ⚖️
