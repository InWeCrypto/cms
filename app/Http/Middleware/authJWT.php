<?php
namespace App\Http\Middleware;

use Tymon\JWTAuth\Facades\JWTAuth;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class authJWT
{
    public function handle($request, \Closure $next)
    {
        try {

            // web登录
            if (! $token = JWTAuth::getToken()) {
                throw new \Exception('请登录!', NOT_LOGIN);
            }
         
            $user = JWTAuth::parseToken()->authenticate();

            if (! $user ) {
                throw new \Exception('Token 失效请重重新登录!', INVALID_TOKEN);
            }
            
            $request->offsetSet('user', $user);
        } catch (\Exception $e) {

            if ($e instanceof TokenExpiredException){
                return fail('Token 过期请重新登录!', EXPIRED_TOKEN);
            } else if ($e instanceof TokenInvalidException){
                return fail('Token 无效请登录!', INVALID_TOKEN);
            } else {
                return fail($e->getMessage(), $e->getCode());
            }
        }
        
        return $next($request);
    }
}