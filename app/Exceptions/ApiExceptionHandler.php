<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    /**
     * Exception → handler method map
     * (نفس فكرة المقال)
     */
    public static array $handlers = [
        BusinessException::class            => 'handleBusinessException',
        AuthenticationException::class      => 'handleAuthenticationException',
        AuthorizationException::class       => 'handleAuthorizationException',
        ValidationException::class          => 'handleValidationException',
        ModelNotFoundException::class       => 'handleNotFoundException',
        NotFoundHttpException::class        => 'handleNotFoundException',
        MethodNotAllowedHttpException::class=> 'handleMethodNotAllowedException',
        QueryException::class               => 'handleQueryException',
        HttpException::class                => 'handleHttpException',
    ];

    /**
     * Entry point (called from bootstrap/app.php)
     */
    public function handle(Throwable $e, Request $request): JsonResponse
    {
        foreach (self::$handlers as $exceptionClass => $method) {
            if ($e instanceof $exceptionClass) {
                return $this->$method($e, $request);
            }
        }

        // Fallback (unknown exception)
        
        // $this->log($e, 'Unhandled exception');

        return response()->json([
            'error' => [
                'type'      => class_basename($e),
                'status'    => 500,
                'message'   => app()->isProduction()
                    ? 'Server Error'
                    : $e->getMessage(),
                'timestamp' => now()->toISOString(),
            ],
        ], 500);
    }

    /* ================= HANDLERS ================= */

    protected function handleBusinessException(
        BusinessException $e,
        Request $request
    ): JsonResponse {
        // $this->log($e, 'Business exception');

        return response()->json([
            'error' => [
                'type'      => class_basename($e),
                'status'    => $e->statusCode(),
                'message'   => $e->getMessage(),
                'timestamp' => now()->toISOString(),
            ],
        ], $e->statusCode());
    }

    protected function handleAuthenticationException(
        AuthenticationException $e,
        Request $request
    ): JsonResponse {
        return response()->json([
            'error' => [
                'type'      => 'AuthenticationException',
                'status'    => 401,
                'message'   => 'Unauthenticated.',
                'timestamp' => now()->toISOString(),
            ],
        ], 401);
    }

    protected function handleAuthorizationException(
        AuthorizationException $e,
        Request $request
    ): JsonResponse {
        return response()->json([
            'error' => [
                'type'      => 'AuthorizationException',
                'status'    => 403,
                'message'   => 'You are not authorized to perform this action.',
                'timestamp' => now()->toISOString(),
            ],
        ], 403);
    }

    protected function handleValidationException(
        ValidationException $e,
        Request $request
    ): JsonResponse {
        return response()->json([
            'error' => [
                'type'      => 'ValidationException',
                'status'    => 422,
                'message'   => 'Validation failed.',
                'errors'    => $e->errors(),
                'timestamp' => now()->toISOString(),
            ],
        ], 422);
    }

    protected function handleNotFoundException(
        Throwable $e,
        Request $request
    ): JsonResponse {
        return response()->json([
            'error' => [
                'type'      => class_basename($e),
                'status'    => 404,
                'message'   => 'Resource not found.',
                'timestamp' => now()->toISOString(),
            ],
        ], 404);
    }

    protected function handleMethodNotAllowedException(
        MethodNotAllowedHttpException $e,
        Request $request
    ): JsonResponse {
        return response()->json([
            'error' => [
                'type'      => 'MethodNotAllowed',
                'status'    => 405,
                'message'   => 'HTTP method not allowed.',
                'timestamp' => now()->toISOString(),
            ],
        ], 405);
    }

    protected function handleQueryException(
        QueryException $e,
        Request $request
    ): JsonResponse {
        // $this->log($e, 'Database error');

        return response()->json([
            'error' => [
                'type'      => 'QueryException',
                'status'    => 500,
                'message'   => 'Database error.',
                'timestamp' => now()->toISOString(),
            ],
        ], 500);
    }

    protected function handleHttpException(
        HttpException $e,
        Request $request
    ): JsonResponse {
        return response()->json([
            'error' => [
                'type'      => class_basename($e),
                'status'    => $e->getStatusCode(),
                'message'   => $e->getMessage(),
                'timestamp' => now()->toISOString(),
            ],
        ], $e->getStatusCode());
    }

    // protected function log(Throwable $e, string $message): void
    // {
    //     Log::warning($message, [
    //         'exception' => get_class($e),
    //         'message'   => $e->getMessage(),
    //         'url'       => request()->fullUrl(),
    //         'method'    => request()->method(),
    //     ]);
    // }
}
