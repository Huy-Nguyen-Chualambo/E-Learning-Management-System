{{-- filepath: resources/views/user/dashboard.blade.php --}}
@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Welcome back, {{ $user->name }}!</h2>
                    <p class="text-muted">Continue your learning journey</p>
                </div>
                <div class="text-end">
                    <small class="text-muted">Your Role: </small>
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{ $role->display_name }}</span>
                    @endforeach
                    
                    {{-- Hiển thị link admin nếu user có quyền --}}
                    @if($user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('manager'))
                        <br>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-danger mt-2">
                            <i class="fas fa-tools me-1"></i>Go to Admin Panel
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Stats -->
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">My Courses</h5>
                            <h3>{{ isset($stats) ? $stats['total_courses'] : $user->courses->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Completed</h5>
                            <h3>{{ isset($stats) ? $stats['completed_courses'] : $user->courses->where('pivot.status', 'completed')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">In Progress</h5>
                            <h3>{{ isset($stats) ? $stats['in_progress_courses'] : $user->courses->where('pivot.status', 'in_progress')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Certificates</h5>
                            <h3>{{ isset($stats) ? $stats['certificates'] : $user->courses->where('pivot.status', 'completed')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-certificate fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-book-open me-2"></i>My Courses
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->courses->count() > 0)
                        <div class="row">
                            @foreach($user->courses as $course)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title">{{ $course->name }}</h6>
                                            <span class="badge bg-{{ $course->pivot->status === 'completed' ? 'success' : ($course->pivot->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($course->pivot->status) }}
                                            </span>
                                        </div>
                                        <p class="card-text small text-muted">{{ Str::limit($course->description, 80) }}</p>
                                        
                                        <!-- Progress Bar -->
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <small>Progress</small>
                                                <small>{{ $course->pivot->progress_percentage }}%</small>
                                            </div>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $course->pivot->progress_percentage ?? 0 }}%"></div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>{{ $course->duration_hours }}h
                                                @if($course->price > 0)
                                                    <span class="ms-2">${{ $course->price }}</span>
                                                @else
                                                    <span class="ms-2 text-success">Free</span>
                                                @endif
                                            </small>
                                            <button class="btn btn-sm btn-primary">Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('user.courses') }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>View All My Courses
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5>No Courses Yet</h5>
                            <p class="text-muted">Browse our course catalog to get started!</p>
                            <a href="#available-courses" class="btn btn-primary">Browse Courses</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Courses Section -->
            @php
                $availableCourses = App\Models\Product::where('is_active', 1)
                    ->whereNotIn('id', $user->courses->pluck('id'))
                    ->limit(6)
                    ->get();
            @endphp

            @if($availableCourses->count() > 0)
            <div class="card mt-4" id="available-courses">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Available Courses
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($availableCourses as $course)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title">{{ $course->name }}</h6>
                                        <span class="badge bg-info">{{ ucfirst($course->level) }}</span>
                                    </div>
                                    <p class="card-text small text-muted">{{ Str::limit($course->description, 60) }}</p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-clock me-1"></i>{{ $course->duration_hours }}h
                                            </small>
                                            <strong class="text-primary">
                                                @if($course->price > 0)
                                                    ${{ number_format($course->price, 2) }}
                                                @else
                                                    Free
                                                @endif
                                            </strong>
                                        </div>
                                        <button class="btn btn-sm btn-success">Enroll Now</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Profile Info
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Member since:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                    <p><strong>Email verified:</strong> 
                        @if($user->email_verified_at)
                            <span class="badge bg-success">Verified</span>
                        @else
                            <span class="badge bg-warning">Not verified</span>
                        @endif
                    </p>
                    
                    <hr>
                    <p><strong>Your Roles:</strong></p>
                    @foreach($user->roles as $role)
                        <span class="badge bg-secondary me-1">{{ $role->display_name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection