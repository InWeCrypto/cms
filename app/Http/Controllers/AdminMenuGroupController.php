<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\AdminMenuGroup;

class AdminMenuGroupController extends BaseController
{
    public function store(Request $request, $user_id)
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
