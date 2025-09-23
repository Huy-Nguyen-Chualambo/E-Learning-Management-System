{{-- filepath: resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-users me-2"></i>Users Management
                </h4>
                @hasPermission('create-users')
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add User
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
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Email Verified</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-info me-1">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">Verified</span>
                                    @else
                                        <span class="badge bg-warning">Not Verified</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @hasPermission('edit-users')
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endhasPermission
                                        
                                        @hasPermission('delete-users')
                                        @if($user->id !== auth()->id())
                                        <form method="POST" 
                                              action="{{ route('admin.users.destroy', $user) }}" 
                                              class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @endhasPermission
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No users found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection