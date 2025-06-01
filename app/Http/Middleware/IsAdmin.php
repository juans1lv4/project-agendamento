<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{

   
    public function handle($request, Closure $next)
    {
        if(!auth()->check() || !auth()->user()->is_admin){
            return response()->json(['error'=>'Acesso nao autorizado nessa porra aq'], 403);
        }
        return $next($request);
    }
}
