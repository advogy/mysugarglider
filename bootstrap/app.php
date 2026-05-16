<?php

use App\Http\Middleware\CheckBanned;
use App\Http\Middleware\DisableBackBtn;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Tambahkan CheckBanned ke semua request web (setelah login)
        $middleware->web(append: [
            CheckBanned::class,
        ]);

        // Alias middleware kustom
        $middleware->alias([
            'DisableBackBtn' => DisableBackBtn::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException|ModelNotFoundException $e, $request) {
            return response()->view('errors.404', ['exception' => $e], 404);
        });

        $exceptions->render(function (AccessDeniedHttpException|AuthorizationException $e, $request) {
            return response()->view('errors.403', ['exception' => $e], 403);
        });
    })->create();
