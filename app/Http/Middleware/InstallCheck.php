<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Http\Middleware;

use Closure;

class InstallCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 安装检查
        $installLock = base_path().DIRECTORY_SEPARATOR.'install.lock';
        if (file_exists($installLock)) {
            return redirect(url('/'));
        }

        return $next($request);
    }
}
