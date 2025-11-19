<?php
// app/Http/Middleware/CheckBuyer.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBuyer
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isBuyer()) {
            abort(403, 'يجب أن تكون مشترياً للوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
?>