{{-- filepath: resources/views/user/profile.blade.php --}}
@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-user me-2"></i>My Profile</h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Member Since</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->created_at->format('F d, Y') }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <div>
                                @foreach(auth()->user()->roles as $role)
                                    <span class="badge bg-primary me-1">{{ $role->display_name }}</span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-primary" disabled>
                                <i class="fas fa-edit me-1"></i>Edit Profile (Coming Soon)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection