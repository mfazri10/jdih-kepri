{{-- Name --}}
<div class="mb-3">
    <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $category->name ?? '') }}" placeholder="Cth: Pendidikan" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Slug --}}
<div class="mb-3">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
           value="{{ old('slug', $category->slug ?? '') }}" placeholder="Otomatis dari nama">
    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Description --}}
<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
              rows="3">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Icon --}}
<div class="mb-3">
    <label class="form-label">Icon (Bootstrap Icons)</label>
    <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
           value="{{ old('icon', $category->icon ?? '') }}" placeholder="Cth: bi-mortarboard">
    @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Parent --}}
<div class="mb-3">
    <label class="form-label">Induk Kategori</label>
    <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
        <option value="">— Tidak Ada Induk —</option>
        @foreach($parents as $parent)
            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                {{ $parent->name }}
            </option>
        @endforeach
    </select>
    @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Sort Order --}}
<div class="mb-3">
    <label class="form-label">Urutan</label>
    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
           value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Active Toggle --}}
<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
           {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Aktif</label>
</div>
