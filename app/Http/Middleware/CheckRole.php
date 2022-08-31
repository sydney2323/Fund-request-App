<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        $userRole = $request->user()->role;
        if ($userRole) {
            // Set scope as admin/moderator based on user role
            $request->request->add([
                'scope' => $userRole
            ]);

           
        }
        // if (Auth::user()->role !== 'staff' || Auth::user()->role !== 'admin'|| Auth::user()->role !== 'finance') {
        //     return response(json_encode(['error' => 'unauthenticated']), 401);
        // }
        
    
        return $next($request);
    }
}
