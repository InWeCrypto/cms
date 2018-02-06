<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\MenuGroupRelation;

class MenuGroupRelationController extends BaseController
{
    public function index(Request $request, $gid)
    {
        $list =  MenuGroupRelation::with('menu')->where('group_id', $gid)->paginate($this->per_page);

        return success($list);
    }
    public function store(Request $request, $gid)
    {
        $validator = \Validator::make($request->all(), [
            'menu_ids' => 'required|array',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }
        $menu_ids = $request->get('menu_ids');
        DB::beginTransaction();
        try {
            $data = [];
            foreach($menu_ids as $menu_id){
                $temp = [];
                $temp['group_id'] = $gid;
                $temp['menu_id'] = $menu_id;
                $temp['created_at'] = date('Y-m-d H:i:s');
                $temp['updated_at'] = date('Y-m-d H:i:s');

                $data[] = $temp;
            }
            if(MenuGroupRelation::where('group_id', $gid)->delete() === false){
                throw new \Exception('清空菜单组失败');
            }
            if(! MenuGroupRelation::insert($data)){
                throw new \Exception('添加菜单组失败');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return fail($e->getMessage());
        }
        return success();
    }

}
