<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        
        // Custom middleware
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        
        // Permission middleware
        'can.view.users' => \App\Http\Middleware\CanViewUsers::class,
        'can.create.users' => \App\Http\Middleware\CanCreateUsers::class,
        'can.edit.users' => \App\Http\Middleware\CanEditUsers::class,
        'can.delete.users' => \App\Http\Middleware\CanDeleteUsers::class,
        
        'can.view.roles' => \App\Http\Middleware\CanViewRoles::class,
        'can.create.roles' => \App\Http\Middleware\CanCreateRoles::class,
        'can.edit.roles' => \App\Http\Middleware\CanEditRoles::class,
        'can.delete.roles' => \App\Http\Middleware\CanDeleteRoles::class,
        
        'can.view.permissions' => \App\Http\Middleware\CanViewPermissions::class,
        'can.create.permissions' => \App\Http\Middleware\CanCreatePermissions::class,
        'can.edit.permissions' => \App\Http\Middleware\CanEditPermissions::class,
        'can.delete.permissions' => \App\Http\Middleware\CanDeletePermissions::class,
    ];
}