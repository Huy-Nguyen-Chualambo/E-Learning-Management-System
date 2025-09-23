{{-- filepath: resources/views/admin/roles/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-user-shield me-2"></i>Roles Management
                </h4>
                @hasPermission('create-roles')
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add Role
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
                                <th>Users Count</th>
                                <th>Permissions</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>
                                    <code>{{ $role->name }}</code>
                                </td>
                                <td>{{ $role->display_name }}</td>
                                <td>{{ $role->description }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $role->users_count ?? $role->users->count() }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#permissions-{{ $role->id }}">
                                        <i class="fas fa-key me-1"></i>
                                        {{ $role->permissions->count() }} permissions
                                    </button>
                                    <div class="collapse mt-2" id="permissions-{{ $role->id }}">
                                        @foreach($role->permissions as $permission)
                                            <span class="badge bg-light text-dark me-1 mb-1">{{ $permission->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ $role->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @hasPermission('view-roles')
                                        <a href="{{ route('admin.roles.show', $role) }}" 
                                           class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endhasPermission
                                        
                                        @hasPermission('edit-roles')
                                        <a href="{{ route('admin.roles.edit', $role) }}" 
                                           class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endhasPermission
                                        
                                        @hasPermission('delete-roles')
                                        @if(!in_array($role->name, ['super-admin', 'admin']))
                                        <form method="POST" 
                                              action="{{ route('admin.roles.destroy', $role) }}" 
                                              class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this role?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @else
                                        <button class="btn btn-outline-secondary" disabled title="System role cannot be deleted">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                        @endif
                                        @endhasPermission
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No roles found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($roles, 'links'))
                    {{ $roles->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection