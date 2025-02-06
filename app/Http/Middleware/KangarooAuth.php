<?php
// app/Http/Middleware/KangarooAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KangarooAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('kangaroo_access_token')) {
            return redirect('/login');
        }
        return $next($request);
    }
}
