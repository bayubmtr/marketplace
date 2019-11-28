<?php

namespace App\Http\Middleware;

use Closure;

class CheckStore
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->store == null) {
            return ok(['message' => 'Anda Belum Memiliki Toko !', 'is_seller' => false]);
        }
        return $next($request);
    }
}
