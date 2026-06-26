@csrf
@if ($user->exists)
    @method('PUT')
@endif

<div class="card stretch stretch-full border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="row g-5">
            <!-- Kolom Kiri -->
            <div class="col-lg-5">
                <h5 class="mb-4 border-bottom pb-2">Informasi Dasar</h5>
                
                <div class="mb-4">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password {{ $user->exists ? '(Opsional)' : '' }}</label>
                    <input type="password" name="password" id="password" class="form-control" {{ $user->exists ? '' : 'required' }}>
                    @if ($user->exists)
                        <div class="form-text">Biarkan kosong jika password tidak diubah.</div>
                    @endif
                </div>
                
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $user->exists ? $user->is_active : true))>
                        <label class="form-check-label fw-semibold" for="is_active">Status Akun Aktif</label>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-lg-7">
                <h5 class="mb-4 border-bottom pb-2">Role & Akses Spesifik</h5>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Role Utama</label>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach ($roles as $role)
                            <div class="form-check me-4 mb-2">
                                <input class="form-check-input" type="checkbox" id="role-{{ $role->id }}" name="roles[]" value="{{ $role->name }}" @checked(in_array($role->name, old('roles', $selectedRoles), true))>
                                <label class="form-check-label text-capitalize" for="role-{{ $role->id }}">{{ str_replace('-', ' ', $role->name) }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="form-label fw-semibold mb-3">Direct Permission</label>
                    <div class="accordion" id="permissionsAccordion">
                        @foreach ($permissionGroups as $group => $permissions)
                            @php $groupId = \Illuminate\Support\Str::slug($group); @endphp
                            <div class="accordion-item rounded border mb-2 shadow-none">
                                <h2 class="accordion-header" id="heading-{{ $groupId }}">
                                    <button class="accordion-button collapsed py-2 px-3 bg-light text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $groupId }}">
                                        {{ str_replace('_', ' ', $group) }}
                                    </button>
                                </h2>
                                <div id="collapse-{{ $groupId }}" class="accordion-collapse collapse" data-bs-parent="#permissionsAccordion">
                                    <div class="accordion-body px-3 py-3">
                                        <div class="row g-2">
                                            @foreach ($permissions as $permission)
                                                <div class="col-sm-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="perm-{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" @checked(in_array($permission->name, old('permissions', $selectedPermissions), true))>
                                                        <label class="form-check-label text-capitalize" for="perm-{{ $permission->id }}">
                                                            {{ str_replace(['_', '.'], ' ', $permission->name) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer bg-transparent border-top p-4 d-flex justify-content-end gap-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</div>
