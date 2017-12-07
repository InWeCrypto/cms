<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Admin;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AdminController extends BaseController
{
    // 登录
    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');
        try {
            // 登录系统并获取token
            if (! $token = JWTAuth::attempt($credentials)) {
                throw new \Exception('登录失败!', CREATE_USER_TOKEN_FAIL);
            }
            $user = JWTAuth::toUser($token);
            $user->Token = 'Bearer ' . $token;
        } catch (\Exception $e) {
            return fail($e->getMessage(), $e->getCode());
        }
        return success($user);
    }

    public function index(Request $request)
    {
        $info = Admin::paginate(5);
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
                'phone' => 'unique:admins,phone',
                'password' => 'required|confirmed|min:6',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
 
        $info = new Admin();
        $info->fill($request->all());
        $info->password = bcrypt($info->password);
        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
                'name' => 'required|unique:users,name,' . $id,
                'email' => 'required|email|unique:users,email,' .$id,
                'phone' => 'unique:users,phone,' .$id,
                'password' => 'confirmed|min:6',
            ]
        );

        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = Admin::find($id);
        $info->fill($request->all());
        if(isset($info->password)){
            $info->password = bcrypt($info->password);
        }
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return Admin::find($id)->delete() ? success() : fail();
    }

}