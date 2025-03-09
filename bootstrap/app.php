<?php

use App\Http\Middleware\AutoloadUserSessionPermissionByOrganizationMiddleware;
use App\Http\Middleware\CheckOrganizationPermission;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\Loggers\LogActivityMiddleware;
use App\Http\Middleware\Posts\UserViewedPostMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
   ->withRouting(
      web: __DIR__ . '/../routes/web.php',
      api: __DIR__ . '/../routes/api.php',
      commands: __DIR__ . '/../routes/console.php',
      health: '/up',
   )
   ->withMiddleware(function (Middleware $middleware) {
      $middleware->web([LocaleMiddleware::class]);
      $middleware->alias([
         'role' => RoleMiddleware::class,
         'role_or_permission' => RoleOrPermissionMiddleware::class,
         'log_activity' => LogActivityMiddleware::class,
         'permission' => CheckOrganizationPermission::class,
         'user_viewed_post' => UserViewedPostMiddleware::class,
         'autoload_user_session_permission' => AutoloadUserSessionPermissionByOrganizationMiddleware::class
      ]);
      $middleware->validateCsrfTokens([
        'ckfinder/*'
      ]);
      $middleware->encryptCookies([
        'ckCsrfToken'
      ]);
   })
   ->withExceptions(function (Exceptions $exceptions) {
      $exceptions->renderable(function (Throwable $exception) {
         Log::debug($exception->getMessage());
      });
   })->create();
