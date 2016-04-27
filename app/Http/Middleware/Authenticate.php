<?php

namespace App\Http\Middleware;

use App\Services\Facebook\FacebookManager;
use Closure;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $logged = \Auth::check();

        if ($logged)
        {
            $isTokenValid = (new FacebookManager())->isTokenValid(\Auth::user()->fb_token);

            if (!$isTokenValid)
            {
                \Auth::logout();
                $logged = false;
            }
        }

        if (!$logged)
        {
            if ($request->ajax() || $request->wantsJson())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                return redirect()->guest('facebook-login');
            }
        } elseif (!\Auth::user()->authorized)
        {
            return redirect()->route('auth.unauthorized');
        }

        return $next($request);
    }
}
