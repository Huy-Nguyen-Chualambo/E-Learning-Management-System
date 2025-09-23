{{-- filepath: resources/views/admin/permissions/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-key me-2"></i>Permissions Management
                </h4>
                @hasPermission('create-permissions')
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add Permission
                </a>
                @endhasPermission
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Description</th>
                                <th>Roles</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td>
                                    <code>{{ $permission->name }}</code>
                                </td>
                                <td>{{ $permission->display_name }}</td>
                                <td>{{ $permission->description }}</td>
                                <td>
                                    @foreach($permission->roles as $role)
                                        <span class="badge bg-info me-1">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $permission->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @hasPermission('edit-permissions')
                                        <a href="{{ route('admin.permissions.edit', $permission) }}" 
                                           class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endhasPermission
                                        
                                        @hasPermission('delete-permissions')
                                        <form method="POST" 
                                              action="{{ route('admin.permissions.destroy', $permission) }}" 
                                              class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this permission?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endhasPermission
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No permissions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($permissions, 'links'))
                    {{ $permissions->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection