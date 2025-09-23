{{-- filepath: resources/views/layouts/admin.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                E-Learning Admin
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                
                @hasPermission('view-users')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users me-1"></i>Users
                    </a>
                </li>
                @endhasPermission
                
                @hasPermission('view-roles')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.roles.index') }}">
                    <i class="fas fa-user-shield me-1"></i>Roles
                    </a>
                </li>
                @endhasPermission
                
                @hasPermission('view-permissions')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.permissions.index') }}">
                    <i class="fas fa-key me-1"></i>Permissions
                    </a>
                </li>
                @endhasPermission
                
                @hasPermission('view-products')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box me-1"></i>Products
                    </a>
                </li>
                @endhasPermission
                
                @hasPermission('view-categories')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-tags me-1"></i>Categories
                    </a>
                </li>
                @endhasPermission
                </ul>

                <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="{{ route('admin.categories.index') }}" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Back to Dashboard</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                    </ul>
                </li>
                </ul>
            </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container-fluid mt-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>