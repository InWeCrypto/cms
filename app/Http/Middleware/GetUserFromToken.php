<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class GetUserFromToken
{
    /**
     * 身份验证.
     *
     * Author : LeePeng
     * email: lp@kuhui.com.cn
     * Date: 15/12/29
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	public function handle($request, Closure $next)
	{
	    try {
            // 客户端登录
            if (! $request['token'] = $request->header('ct')){
                throw new \Exception('请登录!', NOT_LOGIN);
            }
            // 客户端登录是从其他服务器得到的用户(jwt 中的 sub 不一样)
            // 以手机端open_id为准
            $open_id = $request->header('open-id');

            $user = \App\Model\AppWalletUser::where('open_id', $open_id)->first();

            if (! $user ) {
                throw new \Exception('Token 失效请重重新登录!', INVALID_TOKEN);
            }

	        $request->AppUser = $user;
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
