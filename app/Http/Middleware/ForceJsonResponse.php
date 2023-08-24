<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    public function __construct(protected ResponseFactory $factory)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        return $this->formatResponse($next($request));
    }

    protected function formatResponse(Response $response): Response
    {
        return $this->factory->json(
            ['data' => $response->getContent()],
            $response->getStatusCode(),
            $response->headers->all(),
        );
    }
}
