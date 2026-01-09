<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DeliveryAgentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('delivery_men')->check()) {
           if(auth('delivery_men')->user()->delivery_type == 'agent'){
             return $next($request);
           }
            
        }
        return redirect()->route('delivery-agent.auth.login');
    }
}
