@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $usersCount ?? 0 }}</h4>
                        <p class="mb-0">Total Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $productsCount ?? 0 }}</h4>
                        <p class="mb-0">Total Products</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $categoriesCount ?? 0 }}</h4>
                        <p class="mb-0">Total Categories</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-folder fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $rolesCount ?? 0 }}</h4>
                        <p class="mb-0">Total Roles</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-tag fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Products</h5>
            </div>
            <div class="card-body">
                @if(isset($recentProducts) && $recentProducts->count() > 0)
                    <div class="list-group">
                        @foreach($recentProducts as $product)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                    <small>${{ $product->final_price }}</small>
                                </div>
                                <p class="mb-1">{{ Str::limit($product->description, 100) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No products found</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Users</h5>
            </div>
            <div class="card-body">
                @if(isset($recentUsers) && $recentUsers->count() > 0)
                    <div class="list-group">
                        @foreach($recentUsers as $user)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                    <small>{{ $user->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $user->email }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No users found</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection