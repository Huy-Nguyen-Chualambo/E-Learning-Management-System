{{-- filepath: resources/views/admin/products/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-graduation-cap me-2"></i>{{ $product->name }}</h1>
        <div>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Courses
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                             class="img-fluid mb-3 rounded" style="max-height: 300px; width: 100%; object-fit: cover;">
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Course Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Level:</th>
                                    <td>
                                        <span class="badge bg-{{ $product->level === 'beginner' ? 'success' : ($product->level === 'intermediate' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($product->level) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Duration:</th>
                                    <td>{{ $product->duration_hours }} hours</td>
                                </tr>
                                <tr>
                                    <th>Price:</th>
                                    <td>
                                        @if($product->price > 0)
                                            <strong class="text-success">${{ number_format($product->price, 2) }}</strong>
                                        @else
                                            <strong class="text-info">Free</strong>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                        @if($product->is_featured)
                                            <span class="badge bg-warning ms-1">Featured</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Instructor & Categories</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Instructor:</th>
                                    <td>
                                        @if($product->instructor)
                                            <strong>{{ $product->instructor->name }}</strong><br>
                                            <small class="text-muted">{{ $product->instructor->email }}</small>
                                        @else
                                            <span class="text-muted">No instructor assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Categories:</th>
                                    <td>
                                        @forelse($product->categories as $category)
                                            <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                                        @empty
                                            <span class="text-muted">No categories</span>
                                        @endforelse
                                    </td>
                                </tr>
                                <tr>
                                    <th>Slug:</th>
                                    <td><code>{{ $product->slug }}</code></td>
                                </tr>
                                <tr>
                                    <th>Sort Order:</th>
                                    <td>{{ $product->sort_order }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h5>Description</h5>
                    <p class="text-muted">{{ $product->description }}</p>

                    @if($product->content)
                        <h5>Course Content</h5>
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($product->content)) !!}
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Created:</strong> {{ $product->created_at->format('M d, Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <strong>Last Updated:</strong> {{ $product->updated_at->format('M d, Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Course Statistics -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <h3 class="text-primary mb-0">{{ $product->users()->count() }}</h3>
                            <small class="text-muted">Total Enrollments</small>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="text-info mb-0">{{ $product->users()->wherePivot('status', 'enrolled')->count() }}</h5>
                            <small class="text-muted">Enrolled</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-warning mb-0">{{ $product->users()->wherePivot('status', 'in_progress')->count() }}</h5>
                            <small class="text-muted">In Progress</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-success mb-0">{{ $product->users()->wherePivot('status', 'completed')->count() }}</h5>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                    
                    @php
                        $totalEnrolled = $product->users()->count();
                        $completionRate = $totalEnrolled > 0 ? ($product->users()->wherePivot('status', 'completed')->count() / $totalEnrolled * 100) : 0;
                    @endphp
                    
                    <hr>
                    <div class="text-center">
                        <h6>Completion Rate</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $completionRate }}%">
                                {{ number_format($completionRate, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrolled Students -->
            @if($product->users()->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Enrollments</h5>
                </div>
                <div class="card-body">
                    @foreach($product->users()->latest('pivot_created_at')->limit(5)->get() as $student)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $student->name }}</strong><br>
                                <small class="text-muted">{{ $student->email }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $student->pivot->status === 'completed' ? 'success' : ($student->pivot->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($student->pivot->status) }}
                                </span><br>
                                <small class="text-muted">{{ $student->pivot->progress_percentage }}%</small>
                            </div>
                        </div>
                        @if(!$loop->last)<hr class="my-2">@endif
                    @endforeach
                    
                    @if($product->users()->count() > 5)
                        <div class="text-center mt-3">
                            <small class="text-muted">And {{ $product->users()->count() - 5 }} more students...</small>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection