<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Admin;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use SmsVerify;

class AdminController extends BaseController
{
    // 登录
    // public function login(Request $request)
    // {
    //     $credentials = $request->only('name', 'password');
    //     try {
    //         // 登录系统并获取token
    //         if (! $token = JWTAuth::attempt($credentials)) {
    //             throw new \Exception('登录失败!', CREATE_USER_TOKEN_FAIL);
    //         }
    //         $user = JWTAuth::toUser($token);
    //         $user->Token = 'Bearer ' . $token;
    //     } catch (\Exception $e) {
    //         return fail($e->getMessage(), $e->getCode());
    //     }
    //     return success($user);
    // }
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'phone' => 'required|max:11|exists:admins,phone',
            'code' => 'required|max:6|min:6'
        ]);
        if($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }

        try {
            SmsVerify::check($request->get('phone'), $request->get('code'));
            $user = Admin::where('phone', $request->get('phone'))->first();
            if(! $token = JWTAuth::fromUser($user)){
                throw new \Exception('登陆失败!', FAIL);
            }
            $user->token = 'Bearer ' . $token;
        } catch (\Exception $e) {
            return fail($e->getMessage(), $e->getCode());
        }

        return success($user);
    }

    // 获取登陆验证码
    public function getLoginCode(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'phone' => 'required|max:11|exists:admins,phone'
        ]);
        if($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $phone = $request->get('phone');
        try {
            SmsVerify::send($phone);
        } catch (\Exception $e) {
            return fail($e->getMessage(), $e->getCode());
        }
        return success();
    }

    public function index(Request $request)
    {
        $info = Admin::paginate($request->get('per_page', 10));
        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(Admin::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'name' => 'required|unique:admins,name',
                'email' => 'required|unique:admins,email',
                'phone' => 'required|unique:admins,phone',
                // 'password' => 'required|confirmed|min:6',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }

        $info = new Admin();
        $info->fill($request->all());

        $info->password = bcrypt('123456');
        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
                'name' => 'required|unique:users,name,' . $id,
                'email' => 'required|email|unique:users,email,' .$id,
                'phone' => 'required|unique:users,phone,' .$id,
                // 'password_old' => 'min:6',
                // 'password' => 'confirmed|min:6',
            ]
        );

        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = Admin::find($id);
        $info->fill($request->all());
        if(isset($info->password) && ! empty($info->password)){
            $info->password = bcrypt($info->password);
        }
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return Admin::find($id)->delete() ? success() : fail();
    }

    public function resetPassword(Request $request, $user_id = null)
    {
        $validator = \Validator::make($request->all(), [
                'password' => 'required|confirmed|min:6',
                'password_old' => 'required',
            ]
        );

        $user_id = $user_id ?: $request->user()->id;
        $info    = Admin::find($user_id);

        if(! \Hash::check($request->get('password_old'), $info->password)){
            return fail('原始密码错误!');
        }

        $info->password = bcrypt($request->get('password'));
        return $info->save() ? success() : fail();
    }

}
