{{-- filepath: resources/views/admin/products/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-graduation-cap me-2"></i>Courses Management</h1>
        @hasPermission('create-products')
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Course
        </a>
        @endhasPermission
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 mb-3">
                <div class="col-sm-6 col-md-4">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Search courses by name or description">
                </div>
                <div class="col-sm-6 col-md-3">
                    <input type="text" name="category" value="{{ request('category') }}" class="form-control" placeholder="Filter by category name">
                </div>
                <div class="col-sm-6 col-md-2">
                    <input type="number" step="0.01" min="0" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="Min price">
                </div>
                <div class="col-sm-6 col-md-2">
                    <input type="number" step="0.01" min="0" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="Max price">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i>Reset
                    </a>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Course Name</th>
                            <th>Instructor</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Level</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    <br>
                                    @foreach($product->categories as $category)
                                        <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $product->instructor ? $product->instructor->name : 'No Instructor' }}</td>
                                <td>
                                    @if($product->price > 0)
                                        <span class="text-success">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="text-info">Free</span>
                                    @endif
                                </td>
                                <td>{{ $product->duration_hours }}h</td>
                                <td>
                                    <span class="badge bg-{{ $product->level === 'beginner' ? 'success' : ($product->level === 'intermediate' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($product->level) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $product->users()->count() }}</span>
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="badge bg-warning">Featured</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @hasPermission('view-products')
                                        <a href="{{ route('admin.products.show', $product) }}" 
                                           class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endhasPermission
                                        @hasPermission('edit-products')
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endhasPermission
                                        @hasPermission('delete-products')
                                        <form action="{{ route('admin.products.destroy', $product) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this course?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endhasPermission
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                    <h5>No Courses Found</h5>
                                    <p class="text-muted">Start by adding your first course.</p>
                                    @hasPermission('create-products')
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>Add Course
                                    </a>
                                    @endhasPermission
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection