<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\JsonResponse;
use Throwable;

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

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e): JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
    {
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions with consistent JSON responses.
     */
    private function handleApiException(Request $request, Throwable $e): JsonResponse
    {
        $exception = $this->prepareException($e);

        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthenticated',
                'error' => 'Authentication required',
            ], 401);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Resource not found',
                'error' => 'The requested resource could not be found',
            ], 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Route not found',
                'error' => 'The requested endpoint does not exist',
            ], 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => 'Method not allowed',
                'error' => 'The HTTP method is not supported for this route',
            ], 405);
        }

        if ($exception instanceof HttpException) {
            return response()->json([
                'message' => $exception->getMessage() ?: 'HTTP Exception',
                'error' => 'An HTTP error occurred',
            ], $exception->getStatusCode());
        }

        // Log unexpected errors
        if (!config('app.debug')) {
            report($exception);
            
            return response()->json([
                'message' => 'Internal server error',
                'error' => 'An unexpected error occurred',
            ], 500);
        }

        // In debug mode, show detailed error information
        return response()->json([
            'message' => $exception->getMessage(),
            'error' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ], 500);
    }

    /**
     * Convert an authentication exception into a response.
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthenticated',
                'error' => 'Authentication required',
            ], 401);
        }

        // For API-first application, return JSON response even for non-JSON requests
        // since there are no web login routes defined
        return response()->json([
            'message' => 'Unauthenticated',
            'error' => 'Authentication required. Please login via API endpoints: /api/user/login, /api/doctor/login, or /api/admin/login',
        ], 401);
    }
}