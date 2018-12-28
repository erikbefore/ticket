<?php

namespace App\Http\Middleware;

use Closure;

class IsAdminMiddleware
{
    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\PanicHDMember::isAdmin()) {
            return $next($request);
        }

        return redirect()->action('\App\Http\Controllers\TicketsController@index')
            ->with('warning', trans('panichd::lang.you-are-not-permitted-to-access'));
    }
}
