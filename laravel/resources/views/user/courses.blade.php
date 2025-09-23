{{-- filepath: resources/views/user/courses.blade.php --}}
@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">My Courses</h2>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h4>No Courses Yet</h4>
                    <p class="text-muted">You haven't enrolled in any courses yet. Browse our catalog to get started!</p>
                    <a href="#" class="btn btn-primary">Browse Courses</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection