<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\AcceptHeaderItem;

/**
 * Make sure that the response from this API defaults to JSON. Consumers can
 * send the 'Accept' header to override this.
 *
 * @package App\Http\Middleware
 * @since   1.0.0
 */
class DefaultAcceptHeaderToJson
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var AcceptHeaderItem|null $acceptable */
        $acceptableHeader = AcceptHeader::fromString($request->headers->get('Accept'))->first();

        if (!is_null($acceptableHeader)) {
            $acceptable = $acceptableHeader->getValue();
            if ($acceptable !== '*/*' && $acceptable !== '*') {
                return $next($request);
            }
        }

        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
