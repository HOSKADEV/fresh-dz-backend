<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SometimesAuth
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
      $token = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken());
      if($token){
        \Illuminate\Support\Facades\Auth::login($token->tokenable);
      }
        return $next($request);
    }
}
