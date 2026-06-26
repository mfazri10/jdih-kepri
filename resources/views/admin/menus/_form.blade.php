@csrf
@if ($menu->exists)
    @method('PUT')
@endif

<div class="card stretch stretch-full">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <label for="nama_menu" class="form-label">Nama Menu</label>
                <input type="text" name="nama_menu" id="nama_menu" class="form-control"
                    value="{{ old('nama_menu', $menu->nama_menu) }}" required>
            </div>
            <div class="col-md-6">
                <label for="nama_fitur" class="form-label">Nama Fitur</label>
                <input type="text" name="nama_fitur" id="nama_fitur" class="form-control"
                    value="{{ old('nama_fitur', $menu->nama_fitur) }}" placeholder="contoh: users">
            </div>
            <div class="col-md-6">
                <label for="route_name" class="form-label">Route Name</label>
                <select name="route_name" id="route_name" class="form-select">
                    <option value="">- Pilih Route -</option>
                    @foreach ($routeNames as $routeName)
                        <option value="{{ $routeName }}" @selected(old('route_name', $menu->route_name) === $routeName)>
                            {{ $routeName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="alamat_url" class="form-label">Alamat URL</label>
                <input type="text" name="alamat_url" id="alamat_url" class="form-control"
                    value="{{ old('alamat_url', $menu->alamat_url) }}" placeholder="opsional untuk link eksternal">
                <small class="text-muted">Dipakai hanya jika menu tidak mengarah ke route Laravel.</small>
            </div>
            <div class="col-md-6">
                <label for="ikon" class="form-label">Ikon</label>
                <input type="text" name="ikon" id="ikon" class="form-control"
                    value="{{ old('ikon', $menu->ikon) }}" placeholder="contoh: ri-home-line atau feather-home">
            </div>
            <div class="col-md-4">
                <label for="tingkatan_menu" class="form-label">Tingkatan Menu</label>
                <select name="tingkatan_menu" id="tingkatan_menu" class="form-select" required>
                    <option value="parent" @selected(old('tingkatan_menu', $menu->tingkatan_menu) === 'parent')>Parent</option>
                    <option value="child" @selected(old('tingkatan_menu', $menu->tingkatan_menu) === 'child')>Child</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="urutan" class="form-label">Urutan</label>
                <input type="number" name="urutan" id="urutan" class="form-control"
                    value="{{ old('urutan', $menu->urutan ?? 0) }}" min="0" required>
            </div>
            <div class="col-md-4">
                <label for="menu_induk_id" class="form-label">Menu Induk</label>
                <select name="menu_induk_id" id="menu_induk_id" class="form-select">
                    <option value="">- Pilih Induk -</option>
                    @foreach ($parentMenus as $parent)
                        <option value="{{ $parent->id }}" @selected((string) old('menu_induk_id', $menu->menu_induk_id) === (string) $parent->id)>
                            {{ $parent->nama_menu }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="permission_name" class="form-label">Permission Terkait</label>
                <select name="permission_name" id="permission_name" class="form-select">
                    <option value="">- Tanpa Permission -</option>
                    @foreach ($permissions as $permission)
                        <option value="{{ $permission->name }}" @selected(old('permission_name', $menu->permission_name) === $permission->name)>
                            {{ $permission->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="tag" class="form-label">Tag</label>
                <input type="text" name="tag" id="tag" class="form-control" value="{{ old('tag', $menu->tag) }}"
                    placeholder="contoh: pengaturan,navigasi">
            </div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                        @checked(old('is_active', $menu->is_active ?? true))>
                    <label class="form-check-label" for="is_active">Menu aktif</label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end gap-2">
        <a href="{{ route('admin.menus.index') }}" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</div>
