{{-- ═══ IDENTITAS DOKUMEN ═══ --}}
<h6 class="text-uppercase text-muted mb-3" style="letter-spacing: 2px; font-size: 0.8rem;">
    <i class="feather-file-text me-1"></i> Identitas Dokumen
</h6>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
        <select name="type_id" class="form-select @error('type_id') is-invalid @enderror" required>
            <option value="">Pilih Jenis</option>
            @foreach($types as $type)
                <option value="{{ $type->id }}" {{ old('type_id', $document->type_id ?? '') == $type->id ? 'selected' : '' }}>
                    {{ $type->name }} ({{ $type->code }})
                </option>
            @endforeach
        </select>
        @error('type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Nomor <span class="text-danger">*</span></label>
        <input type="text" name="number" class="form-control @error('number') is-invalid @enderror"
               value="{{ old('number', $document->number ?? '') }}" placeholder="Cth: 12" required>
        @error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Tahun <span class="text-danger">*</span></label>
        <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
               value="{{ old('year', $document->year ?? date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}" required>
        @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Judul Lengkap <span class="text-danger">*</span></label>
    <textarea name="title" class="form-control @error('title') is-invalid @enderror"
              rows="3" placeholder="Judul lengkap peraturan..." required>{{ old('title', $document->title ?? '') }}</textarea>
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Tajuk Entri Utama (TEU) <span class="text-danger">*</span></label>
    <input type="text" name="teu" class="form-control @error('teu') is-invalid @enderror"
           value="{{ old('teu', $document->teu ?? '') }}" placeholder="Cth: Peraturan Daerah Provinsi Kepulauan Riau" required>
    @error('teu') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
           value="{{ old('slug', $document->slug ?? '') }}" placeholder="Otomatis dari judul+nomor+tahun">
    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<hr class="my-4">

{{-- ═══ KLASIFIKASI ═══ --}}
<h6 class="text-uppercase text-muted mb-3" style="letter-spacing: 2px; font-size: 0.8rem;">
    <i class="feather-layers me-1"></i> Klasifikasi
</h6>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
            <option value="">Pilih Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $document->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="berlaku" {{ old('status', $document->status ?? 'berlaku') === 'berlaku' ? 'selected' : '' }}>Berlaku</option>
            <option value="dicabut" {{ old('status', $document->status ?? '') === 'dicabut' ? 'selected' : '' }}>Dicabut</option>
            <option value="tidak_berlaku" {{ old('status', $document->status ?? '') === 'tidak_berlaku' ? 'selected' : '' }}>Tidak Berlaku</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Bahasa</label>
        <input type="text" name="language" class="form-control @error('language') is-invalid @enderror"
               value="{{ old('language', $document->language ?? 'id') }}" placeholder="id">
        @error('language') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Subjek Hukum</label>
        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
               value="{{ old('subject', $document->subject ?? '') }}" placeholder="Cth: Pendidikan">
        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Sumber</label>
        <input type="text" name="source" class="form-control @error('source') is-invalid @enderror"
               value="{{ old('source', $document->source ?? '') }}" placeholder="Cth: DPRD Provinsi Kepri">
        @error('source') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Penandatangan</label>
        <input type="text" name="signatory" class="form-control @error('signatory') is-invalid @enderror"
               value="{{ old('signatory', $document->signatory ?? '') }}" placeholder="Cth: Gubernur Kepri">
        @error('signatory') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Tempat Terbit</label>
    <input type="text" name="place" class="form-control @error('place') is-invalid @enderror"
           value="{{ old('place', $document->place ?? '') }}" placeholder="Cth: Tanjungpinang">
    @error('place') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<hr class="my-4">

{{-- ═══ TANGGAL ═══ --}}
<h6 class="text-uppercase text-muted mb-3" style="letter-spacing: 2px; font-size: 0.8rem;">
    <i class="feather-calendar me-1"></i> Tanggal
</h6>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Tanggal Penetapan</label>
        <input type="date" name="date_set" class="form-control @error('date_set') is-invalid @enderror"
               value="{{ old('date_set', isset($document->date_set) ? $document->date_set->format('Y-m-d') : '') }}">
        @error('date_set') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanggal Pengundangan</label>
        <input type="date" name="date_publish" class="form-control @error('date_publish') is-invalid @enderror"
               value="{{ old('date_publish', isset($document->date_publish) ? $document->date_publish->format('Y-m-d') : '') }}">
        @error('date_publish') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanggal Berlaku</label>
        <input type="date" name="date_effective" class="form-control @error('date_effective') is-invalid @enderror"
               value="{{ old('date_effective', isset($document->date_effective) ? $document->date_effective->format('Y-m-d') : '') }}">
        @error('date_effective') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<hr class="my-4">

{{-- ═══ KONTEN ═══ --}}
<h6 class="text-uppercase text-muted mb-3" style="letter-spacing: 2px; font-size: 0.8rem;">
    <i class="feather-align-left me-1"></i> Konten
</h6>

<div class="mb-3">
    <label class="form-label">Abstrak / Ringkasan</label>
    <textarea name="abstract" class="form-control @error('abstract') is-invalid @enderror"
              rows="4" placeholder="Ringkasan singkat dokumen...">{{ old('abstract', $document->abstract ?? '') }}</textarea>
    @error('abstract') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Full Text</label>
    <textarea name="full_text" class="form-control @error('full_text') is-invalid @enderror"
              rows="6" placeholder="Isi lengkap dokumen untuk pencarian full-text...">{{ old('full_text', $document->full_text ?? '') }}</textarea>
    @error('full_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<hr class="my-4">

{{-- ═══ RELASI ═══ --}}
<h6 class="text-uppercase text-muted mb-3" style="letter-spacing: 2px; font-size: 0.8rem;">
    <i class="feather-link me-1"></i> Relasi
</h6>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Tematik</label>
        <select name="themes[]" class="form-select @error('themes') is-invalid @enderror" multiple size="4">
            @foreach($themes as $theme)
                <option value="{{ $theme->id }}"
                    {{ in_array($theme->id, old('themes', $document?->themes?->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                    {{ $theme->name }}
                </option>
            @endforeach
        </select>
        <div class="form-text">Tekan Ctrl untuk pilih lebih dari satu.</div>
        @error('themes') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Tag</label>
        <select name="tags[]" class="form-select @error('tags') is-invalid @enderror" multiple size="4">
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}"
                    {{ in_array($tag->id, old('tags', $document?->tags?->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
        <div class="form-text">Tekan Ctrl untuk pilih lebih dari satu.</div>
        @error('tags') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<hr class="my-4">

{{-- ═══ PUBLISH ═══ --}}
<h6 class="text-uppercase text-muted mb-3" style="letter-spacing: 2px; font-size: 0.8rem;">
    <i class="feather-globe me-1"></i> Publikasi
</h6>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1"
                   {{ old('is_featured', $document->is_featured ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">Dokumen Unggulan</label>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanggal Publikasi</label>
        <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror"
               value="{{ old('published_at', isset($document->published_at) ? $document->published_at->format('Y-m-d\TH:i') : '') }}">
        @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<hr class="my-4">

{{-- ═══ LAMPIRAN ═══ --}}
<h6 class="text-uppercase text-muted mb-3" style="letter-spacing: 2px; font-size: 0.8rem;">
    <i class="feather-paperclip me-1"></i> Lampiran
</h6>

{{-- Existing attachments --}}
@if(isset($document) && $document->attachments->isNotEmpty())
    <div class="mb-3">
        <label class="form-label">Lampiran Saat Ini</label>
        <div class="list-group">
            @foreach($document->attachments as $attachment)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="feather-file me-2"></i>
                        <a href="{{ $attachment->file_url }}" target="_blank">{{ $attachment->original_name }}</a>
                        <small class="text-muted ms-2">({{ $attachment->formatted_size }})</small>
                    </div>
                    <form action="{{ route('admin.documents.attachments.destroy', [$document, $attachment]) }}"
                          method="POST" onsubmit="return confirm('Hapus lampiran ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="feather-trash-2"></i>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="mb-3">
    <label class="form-label">{{ isset($document) ? 'Tambah Lampiran Baru' : 'Upload Lampiran' }}</label>
    <input type="file" name="attachments[]" class="form-control @error('attachments') is-invalid @enderror"
           multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
    <div class="form-text">Format: PDF, DOC, DOCX, JPG, PNG. Maks 50MB per file. Bisa pilih lebih dari satu.</div>
    @error('attachments') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @error('attachments.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
