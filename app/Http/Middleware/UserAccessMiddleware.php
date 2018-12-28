<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Setting;
use App\Traits\TicketRoutes;

class UserAccessMiddleware 
{
    use TicketRoutes;
	/**
     * Session user has at least user level on route or specified resource
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$member = \PanicHDMember::findOrFail(auth()->user()->id);
		
		// Granted to all Admins		
		if ($member->isAdmin()) {
            return $next($request);
        }
		
		// Get Ticket instance. Fails if not found
		$ticket = $this->getRouteTicket($request);
		
		// Ticket Owner has access
		if ($member->isTicketOwner($ticket->id)) {
			return $next($request);
		}

		if ($member->isAgent()) {
			// Assigned Agent has access always
			if ($member->isAssignedAgent($ticket->id)){
				return $next($request);
			}
			
			if ($member->currentLevel() > 1 and Setting::grab('agent_restrict') == 0){
				// Check if element is a visible item for this agent
				if ($member->categories()->where('id',$ticket->category_id)->count() == 1){
					return $next($request);
				}
			}
		}
		
		// Disable comment store for foreign user
		if ($this->mod_route_prefix != 'comment') {
			// Tickets from users in a visible ticketit_department value for current user
			if (in_array($ticket->user_id, $member->getMyNoticesUsers())){
				return $next($request);
			}
		} 
		
        return redirect()->action('\App\Http\Controllers\TicketsController@index')
            ->with('warning', trans('panichd::lang.you-are-not-permitted-to-access'));
    }
}