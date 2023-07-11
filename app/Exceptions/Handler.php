<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Traits\ResponseBuilder;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseBuilder;

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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Exception  $e
     *
     * @throws Exception|Throwable
     */
    public function render($request, $e): JsonResponse
    {
        if ($e instanceof ThrottleRequestsException) {
            return $this->resourcesResponseBuilder(
                status: false,
                code: Response::HTTP_TOO_MANY_REQUESTS,
                message: 'Too many requests.'
            );
        } elseif ($e instanceof ModelNotFoundException && $request->wantsJson()) {
            return $this->resourcesResponseBuilder(
                status: false,
                code: Response::HTTP_NOT_FOUND,
                message: 'Resource '.str_replace(
                    search: 'App', replace: '',
                    subject: $e->getModel()).' not found.'
            );
        } elseif ($e instanceof NotFoundHttpException) {
            return $this->resourcesResponseBuilder(
                status: false,
                code: Response::HTTP_NOT_FOUND,
                message: 'Route not found.',
                description: 'The resources your are looking for does not exist.'
            );
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return $this->resourcesResponseBuilder(
                status: false,
                code: Response::HTTP_METHOD_NOT_ALLOWED,
                message: 'Method not allowed',
                description: 'You are not allowed to perform this action.'
            );
        } elseif ($e instanceof QueryException) {
            return $this->resourcesResponseBuilder(
                status: false,
                code: Response::HTTP_UNAUTHORIZED,
                message: 'Invalid database query.',
                description: $e->getMessage()
            );
        } elseif ($e instanceof RelationNotFoundException) {
            return $this->resourcesResponseBuilder(
                status: false,
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: 'Undefined relationship.'
            );
        } elseif ($e instanceof AuthenticationException) {
            return $this->resourcesResponseBuilder(
                status: false,
                code: Response::HTTP_UNAUTHORIZED,
                message: 'Unauthorised Request.',
                description: 'User not authenticated to perform this action.'
            );
        } elseif ($e instanceof AuthorizationException) {
            return $this->resourcesResponseBuilder(
                status: false,
                code: Response::HTTP_FORBIDDEN,
                message: 'This action is unauthorized.'
            );
        } elseif ($e instanceof AccessDeniedHttpException) {
            return $this->resourcesResponseBuilder(
                status: false,
                code: Response::HTTP_FORBIDDEN,
                message: 'This action is unauthorized.'
            );
        }

        return parent::render($request, $e);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
