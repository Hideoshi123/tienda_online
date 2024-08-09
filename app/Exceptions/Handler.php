<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // public function render($request, Throwable $exception)
    // {
    //     if ($exception instanceof AuthorizationException) {
    //         // Maneja errores de autorización (403)
    //         return response()->view('errors.403', [], 403);
    //     }

    //     if ($exception instanceof NotFoundHttpException) {
    //         // Maneja errores 404 (Página no encontrada)
    //         return response()->view('errors.404', [], 404);
    //     }

    //     return parent::render($request, $exception);
    // }
}
