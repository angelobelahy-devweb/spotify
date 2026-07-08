<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        //return $next($request);

        if (Auth::check() && Auth::user()->role?->name === 'admin') {
            return $next($request);
        }

        // 2. Si ce n'est pas un admin, on le redirige ou on lève une erreur 403
        abort(403, "Accès non autorisé. Cette zone est réservée aux administrateurs.");
    }
}
