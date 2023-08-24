<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    private const DEFAULT_EXCEPTION_CODE = 400;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->renderable(function (Throwable $e) {
            $code = $e->getCode();
            if ($e instanceof HttpExceptionInterface) {
                $code = $e->getStatusCode();
            }

            $message = app()->hasDebugModeEnabled() ? $e->getMessage() : 'Упс, что-то пошло не так';

            return response(['error' => $message], $code ?: self::DEFAULT_EXCEPTION_CODE);
        });
    }
}
