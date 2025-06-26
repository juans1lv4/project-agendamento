<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;



class IsAdmin
{

   
    public function handle($request, Closure $next)
    {
        
        if (!Auth::check() || !Auth::user()->is_admin) {
    return response()->json(['error' => 'Acesso não autorizado'], 403);
}
        return $next($request);
    }
}
