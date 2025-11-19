<?php  
// app/Http/Middleware/CheckSeller.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSeller
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isSeller()) {
            abort(403, 'يجب أن تكون بائعاً للوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
?>