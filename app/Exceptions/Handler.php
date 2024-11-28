<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Проверяем, является ли запрос API-запросом
        if ($request->expectsJson()) {

            // Обработка ошибок валидации
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'error' => 'Validation Error',
                    'messages' => $exception->errors(),
                ], 422);
            }

            // Обработка HTTP-ошибок (например, 404, 403)
            if ($exception instanceof HttpException) {
                return response()->json([
                    'error' => $exception->getMessage() ?: 'HTTP Error',
                ], $exception->getStatusCode());
            }

            // Обработка других исключений
            return response()->json([
                'error' => 'Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }

        // Для не-API запросов используем стандартный обработчик
        return parent::render($request, $exception);
    }
}
