<?php

declare(strict_types=1);

namespace App\Http\Middleware\Keys;

use App\Traits\v1\ResponseBuilder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ApiKeyMiddleware
{
    use ResponseBuilder;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('API-KEY');

        if ($apiKey !== config('services.keys.api_key')) {
            return $this->resourcesResponseBuilder(false, Response::HTTP_UNAUTHORIZED, 'Unauthorised action.', 'Invalid API key.');
        }

        return $next($request);
    }
}
