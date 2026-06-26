@csrf
@if ($permission->exists)
    @method('PUT')
@endif

<div class="card stretch stretch-full">
    <div class="card-body">
        <div class="mb-4">
            <label for="name" class="form-label">Nama Permission</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $permission->name) }}"
                placeholder="contoh: users.view" required>
            <small class="text-muted">Gunakan format `modul.aksi`, misalnya `menus.create` atau `roles.update`.</small>
        </div>

        <div class="border rounded-3 p-3 bg-light">
            <div class="fw-semibold mb-2">Modul yang saat ini disarankan</div>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($modules as $module)
                    <span class="badge bg-soft-primary text-primary">{{ $module }}</span>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end gap-2">
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</div>
