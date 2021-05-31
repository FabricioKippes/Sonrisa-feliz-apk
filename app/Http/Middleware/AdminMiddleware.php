<?php

namespace App\Http\Middleware;

use App\Usuario;
use Closure;
use App\Http\Controllers\Api\Resources\BaseApi;
use App\Http\Controllers\Api\Resources\ResponsePackage;

class AdminMiddleware
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
      $package = new ResponsePackage();

      if (auth()->check() && auth()->user()->isAdmin())
        return $next($request);

      return $package->setError('Usuario no autorizado', BaseApi::HTTP_AUTH_ERROR)
        ->toResponse();
    }
}
