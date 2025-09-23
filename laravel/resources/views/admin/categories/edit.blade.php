{{-- filepath: resources/views/admin/categories/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-edit me-2"></i>Edit Category: {{ $category->name }}</h1>
        <div>
            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-1"></i>View
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">Parent Category</label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror" 
                                            id="parent_id" name="parent_id">
                                        <option value="">Root Category</option>
                                        @foreach($parentCategories as $parent)
                                            <option value="{{ $parent->id }}" 
                                                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image URL</label>
                            <input type="url" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" value="{{ old('image', $category->image) }}" 
                                   placeholder="https://example.com/image.jpg">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" 
                                       name="is_active" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Status
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Category Info</h5>
                </div>
                <div class="card-body">
                    <p><strong>Slug:</strong> <code>{{ $category->slug }}</code></p>
                    <p><strong>Created:</strong> {{ $category->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Updated:</strong> {{ $category->updated_at->format('M d, Y H:i') }}</p>
                    <p><strong>Products:</strong> {{ $category->products()->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection