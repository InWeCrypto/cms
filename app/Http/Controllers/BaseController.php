<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

/**
 * Class BaseController
 * @package App\Http\Controllers\Back
 */
class BaseController extends Controller
{
	/**
	 * @var
	 */
	protected $user;

    public $per_page = 10;

	/**
	 * BaseController constructor.
	 */
	public function __construct(Request $request)
	{
        $this->per_page = request('per_page', 10);
        try {
            $this->user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof TokenExpiredException){
            } else if ($e instanceof TokenInvalidException){
            } else {
                return fail($e->getMessage(), $e->getCode());
            }
        }
	}

    public function msgGroups($type, $lang='zh')
    {
        return \App\Model\EasemobGroup::withoutGlobalScope('lang')
                                        ->where('type', $type)
                                        ->where('lang', $lang)
                                        ->pluck('group_id')
                                        ->toArray();
    }

    // 发送组消息
    public function sendGroupMsg($type, $txt, $lang = 'zh', $from = 'admin')
    {
        $txt = strip_tags($txt);
        $groups = $this->msgGroups($type, $lang);
        return \EasemobMsg::case($type)
                        ->from('admin')
                        ->group($groups)
                        ->txt($txt)
                        ->send();
    }
    // 发送用户消息
    public function sendUserMsg($type, $txt, $users)
    {
        $txt = strip_tags($txt);
        $from = \App\Model\EasemobGroup::$types[$type];
        return \EasemobMsg::case($type)
                        ->from($from)
                        ->user($users)
                        ->txt($txt)
                        ->send();
    }
}
