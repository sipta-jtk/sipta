<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Support\ViewErrorBag;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


public function render($request, Throwable $exception)
{
    $errors = session()->get('errors', app(ViewErrorBag::class));

    if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
        return response()->view('UserManagement.views.error403', ['errors' => $errors], 403);
    }

    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
        return response()->view('UserManagement.views.error404', ['errors' => $errors], 404);
    }

    
    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() === 503) {
    return response()->view('UserManagement.views.error503', ['errors' => $errors], 503);
    }

    return parent::render($request, $exception);
}

    

}
