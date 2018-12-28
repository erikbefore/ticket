<?php

namespace App\Http\Middleware;

use App\Model\Category;
use App\Model\Priority;
use App\Model\Status;
use Closure;

class EnvironmentReadyMiddleware
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
		if (\PanicHDMember::count() != 0
			and Category::count() != 0
			and Priority::count() != 0
			and Status::count() != 0){
			
			return $next($request);
		}
		return redirect()->back()->with('warning', trans('panichd::lang.environment-not-ready'));
    }
}
