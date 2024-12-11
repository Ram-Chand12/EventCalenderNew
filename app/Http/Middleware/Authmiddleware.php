<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authmiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // This is auth middleware to check the user is log in or not then access the next page 
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::check()) {          
            
            return $next($request);
        } else {
            
            return redirect('/login');
        }
    }

}
