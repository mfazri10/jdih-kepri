{{-- Name --}}
<div class="mb-3">
    <label class="form-label">Nama Tematik <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $theme->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Slug --}}
<div class="mb-3">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
           value="{{ old('slug', $theme->slug ?? '') }}" placeholder="Otomatis dari nama">
    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Description --}}
<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
              rows="3">{{ old('description', $theme->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Icon --}}
<div class="mb-3">
    <label class="form-label">Icon (Bootstrap Icons)</label>
    <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
           value="{{ old('icon', $theme->icon ?? '') }}" placeholder="Cth: bi-building">
    @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Color --}}
<div class="mb-3">
    <label class="form-label">Warna</label>
    <div class="d-flex align-items-center gap-2">
        <input type="color" name="color" class="form-control form-control-color @error('color') is-invalid @enderror"
               value="{{ old('color', $theme->color ?? '#2563eb') }}" style="width: 50px; height: 38px;">
        <input type="text" class="form-control @error('color') is-invalid @enderror"
               value="{{ old('color', $theme->color ?? '#2563eb') }}" placeholder="#2563eb"
               oninput="this.previousElementSibling.value = this.value">
    </div>
    @error('color') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

{{-- Sort Order --}}
<div class="mb-3">
    <label class="form-label">Urutan</label>
    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
           value="{{ old('sort_order', $theme->sort_order ?? 0) }}" min="0">
    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Active Toggle --}}
<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
           {{ old('is_active', $theme->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Aktif</label>
</div>
