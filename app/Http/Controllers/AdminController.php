<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Admin;
use App\Model\AdminMenuGroup;
use SmsVerify;
use JWTAuth;

class AdminController extends BaseController
{
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
            $user = Admin::with('menuGroup.menus')->where('phone', $request->get('phone'))->first();
            if(! $token = JWTAuth::fromUser($user)){
                throw new \Exception('登陆失败!', FAIL);
            }
            $user->token = 'Bearer ' . $token;
        } catch (\Exception $e) {
            return fail($e->getMessage(), $e->getCode());
        }

        return success($user);
    }

    public function index(Request $request)
    {
        $list = Admin::with('menuGroup.info')->paginate($this->per_page);

        return success($list);
    }
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'email',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }
        $info = new Admin();
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function update(Request $request, $id)
    {
        $info = Admin::find($id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $id)
    {
        $info = Admin::find($id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $id)
    {
        $info = Admin::with('menuGroup.menus')->find($id);
        return success($info);
    }

    public function updateMenuGroup(Request $request, $user_id)
    {
        $validator = \Validator::make($request->all(), [
            'group_id' => 'required|integer',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }
        DB::beginTransaction();
        try {
            if(AdminMenuGroup::where('user_id', $user_id)->delete() === false){
                throw new \Exception('清空用户菜单组失败');
            }
            if(! AdminMenuGroup::create(['group_id'=>$request->get('group_id'), 'user_id'=>$user_id])){
                throw new \Exception('关联菜单失败');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return fail($e->getMessage());
        }
        return success();
    }

}
