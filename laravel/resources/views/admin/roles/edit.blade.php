{{-- filepath: resources/views/admin/roles/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Role: {{ $role->display_name }}
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $role->name) }}" 
                                       {{ in_array($role->name, ['super-admin', 'admin']) ? 'readonly' : '' }}>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('display_name') is-invalid @enderror" 
                                       id="display_name" 
                                       name="display_name" 
                                       value="{{ old('display_name', $role->display_name) }}">
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row">
                            @foreach($permissions as $group => $groupPermissions)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-capitalize">{{ $group }}</h6>
                                        <small class="text-muted">
                                            <input type="checkbox" class="form-check-input me-1" 
                                                   onchange="toggleGroup(this, '{{ $group }}')"
                                                   {{ $groupPermissions->whereIn('id', $role->permissions->pluck('id'))->count() == $groupPermissions->count() ? 'checked' : '' }}>
                                            Select All
                                        </small>
                                    </div>
                                    <div class="card-body py-2">
                                        @foreach($groupPermissions as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input permission-{{ $group }}" 
                                                   type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->id }}" 
                                                   id="permission-{{ $permission->id }}"
                                                   {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                <small>{{ $permission->display_name }}</small>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleGroup(checkbox, group) {
    const groupCheckboxes = document.querySelectorAll('.permission-' + group);
    groupCheckboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
}
</script>
@endsection