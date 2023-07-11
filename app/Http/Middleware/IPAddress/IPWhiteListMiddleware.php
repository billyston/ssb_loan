<?php

declare(strict_types=1);

namespace App\Http\Middleware\IPAddress;

use App\Traits\ResponseBuilder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IPWhiteListMiddleware
{
    use ResponseBuilder;

    public array $ips = ['172.18.0.1', '172.21.0.1'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->ip(), $this->ips)) {
            return $this->resourcesResponseBuilder(
                status: false,
                code: Response::HTTP_UNAUTHORIZED,
                message: 'Unauthorised access.',
                description: 'Your ip address is not whitelisted'
            );
        }

        return $next($request);
    }
}
