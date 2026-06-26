@csrf
@if ($role->exists)
    @method('PUT')
@endif

<div class="card stretch stretch-full border-0 shadow-sm">
    <div class="card-body p-4">
        
        <h5 class="mb-4 border-bottom pb-2">Informasi Role</h5>
        
        <div class="mb-4">
            <label for="name" class="form-label">Nama Role</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name) }}" required>
        </div>

        <h5 class="mb-4 mt-5 border-bottom pb-2">Matrix Akses (Permissions)</h5>
        
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
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]"
                                                value="{{ $permission->name }}" @checked(in_array($permission->name, old('permissions', $selectedPermissions), true))>
                                            <label class="form-check-label text-capitalize" for="permission-{{ $permission->id }}">
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
    
    <div class="card-footer bg-transparent border-top p-4 d-flex justify-content-end gap-2">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
    </div>
</div>
