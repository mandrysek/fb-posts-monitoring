<?php

namespace App\Http\Middleware;

use Closure;

class NotClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!\Auth::user()->client) {

            return $next($request);
        }

        return redirect()->route('posts.evaluating');
    }
}
