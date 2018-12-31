<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Setting;

class RequiredSettingMiddleware
{
    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $controller)
    {
        if ($controller == "Notices"){
			if (Setting::grab('departments_notices_feature')) {
				return $next($request);
			}
		}else{
			return $next($request);
		}		

        return redirect()->action('\App\Http\Controllers\TicketsController@index')
            ->with('warning', trans('panichd::lang.you-are-not-permitted-to-access'));
    }
}
