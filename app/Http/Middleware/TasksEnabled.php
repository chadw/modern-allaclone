<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TasksEnabled
{
    /**
     * Return 404 if tasks disabled in config.
     *
     * @param  mixed $request
     * @param  mixed $next
     * @return void
     */
    public function handle(Request $request, Closure $next)
    {
        if (!config('everquest.tasks.enable', true)) {
            abort(404);
        }

        return $next($request);
    }
}
