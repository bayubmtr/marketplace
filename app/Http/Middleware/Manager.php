<?php

namespace App\Http\Middleware;

use Closure;

class Manager
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
        if (auth()->user()->user_role_id != 111) {
            return not_acceptable('Anda Dilarang Mengakses Alamat Ini !', $errors = [], $status = 406);
        }
        return $next($request);
    }
}
