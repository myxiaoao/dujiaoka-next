<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Http\Middleware;

use App\Providers\AppServiceProvider;
use Closure;

class DujiaoSystem
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 检测https
        if ($request->getScheme() == 'https') {
            $httpsConfig = [
                'https' => true,
            ];
            config([
                'admin' => array_merge(config('admin'), $httpsConfig),
            ]);
            (new AppServiceProvider(app()))->register();
        }

        return $next($request);
    }
}
