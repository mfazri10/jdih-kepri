<div class="mb-3">
    <label class="form-label">Judul <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $hearing->title ?? '') }}" required>
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $hearing->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', isset($hearing->start_date) ? $hearing->start_date->format('Y-m-d') : '') }}" required>
        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', isset($hearing->end_date) ? $hearing->end_date->format('Y-m-d') : '') }}" required>
        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
        <option value="open" {{ old('status', $hearing->status ?? 'open') === 'open' ? 'selected' : '' }}>Buka</option>
        <option value="closed" {{ old('status', $hearing->status ?? '') === 'closed' ? 'selected' : '' }}>Tutup</option>
        <option value="archived" {{ old('status', $hearing->status ?? '') === 'archived' ? 'selected' : '' }}>Arsip</option>
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Draft Dokumen (URL)</label>
    <input type="text" name="document_draft" class="form-control" value="{{ old('document_draft', $hearing->document_draft ?? '') }}">
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">Lokasi Offline</label>
        <input type="text" name="location" class="form-control" value="{{ old('location', $hearing->location ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Link Online</label>
        <input type="text" name="online_link" class="form-control" value="{{ old('online_link', $hearing->online_link ?? '') }}">
    </div>
</div>
