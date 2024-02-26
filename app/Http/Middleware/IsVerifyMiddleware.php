<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsVerifyMiddleware
{
    use ResponseTrait;
    /**;
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()->is_verify == false)
            return $this->Response(null,"verify Your Account",400);

        return $next($request);
    }
}
