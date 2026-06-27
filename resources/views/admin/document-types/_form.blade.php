{{-- Name --}}
<div class="mb-3">
    <label class="form-label">Nama Jenis <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $documentType->name ?? '') }}" placeholder="Cth: Peraturan Daerah" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Code --}}
<div class="mb-3">
    <label class="form-label">Kode <span class="text-danger">*</span></label>
    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
           value="{{ old('code', $documentType->code ?? '') }}" placeholder="Cth: PERDA" required
           style="text-transform: uppercase;">
    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Description --}}
<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
              rows="3" placeholder="Deskripsi singkat...">{{ old('description', $documentType->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Parent --}}
<div class="mb-3">
    <label class="form-label">Induk Jenis</label>
    <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
        <option value="">— Tidak Ada Induk —</option>
        @foreach($parents as $parent)
            <option value="{{ $parent->id }}" {{ old('parent_id', $documentType->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                {{ $parent->name }} ({{ $parent->code }})
            </option>
        @endforeach
    </select>
    @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Sort Order --}}
<div class="mb-3">
    <label class="form-label">Urutan</label>
    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
           value="{{ old('sort_order', $documentType->sort_order ?? 0) }}" min="0">
    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Active Toggle --}}
<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
           {{ old('is_active', $documentType->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Aktif</label>
</div>
