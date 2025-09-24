@extends('layouts.user')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-graduation-cap me-2"></i>Courses</h2>
        <form method="GET" action="{{ route('user.courses') }}" class="d-flex gap-2">
            <input type="text" name="q" value="{{ $keyword }}" class="form-control" placeholder="Search courses...">
            <select name="category" class="form-select">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ $categorySlug === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-outline-primary" type="submit">Filter</button>
        </form>
    </div>

    <div class="row g-3">
        @forelse($products as $product)
        <div class="col-md-4">
            <div class="card h-100">
                <img src="{{ $product->image_path }}" class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($product->description, 90) }}</p>
                    <div class="mb-2">
                        @foreach($product->categories as $c)
                            <span class="badge bg-secondary me-1">{{ $c->name }}</span>
                        @endforeach
                    </div>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold">{{ $product->formatted_price }}</span>
                        <a href="{{ route('user.courses.show', $product->id) }}" class="btn btn-sm btn-primary">View</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">No courses found.</div>
        </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection


