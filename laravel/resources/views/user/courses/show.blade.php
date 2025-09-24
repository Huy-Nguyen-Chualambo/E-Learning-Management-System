@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <img src="{{ $product->image_path }}" class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body">
                    <h3 class="card-title">{{ $product->name }}</h3>
                    <p class="card-text">{{ $product->description }}</p>
                    <div class="mb-2">
                        @foreach($product->categories as $c)
                            <span class="badge bg-secondary me-1">{{ $c->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold">Price</span>
                        <span>{{ $product->formatted_price }}</span>
                    </div>
                    @if($product->instructor)
                    <div class="mb-2">
                        <small class="text-muted">Instructor</small>
                        <div>{{ $product->instructor->name }}</div>
                    </div>
                    @endif
                    <a href="#" class="btn btn-primary w-100">Enroll</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


