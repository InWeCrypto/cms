<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\User;

class UserController extends BaseController
{
    public function index(Request $request)
    {
        $list = User::whereRaw('1=1');
        if($lang = $request->get('lang')){
            $list = $list->where('lang', $lang);
        }
        if($keyword = $request->get('keyword')){
            $list = $list->where(function($query) use($keyword) {
                $query->orWhere('name', 'like', '%'.$keyword.'%');
                $query->orWhere('email', 'like', '%'.$keyword.'%');
            });
        }
        $list = $list->paginate($this->per_page);

        return success($list);
    }
    public function update(Request $request, $id)
    {
        $info = User::find($id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $id)
    {
        $info = User::find($id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $id)
    {
        $info = User::find($id);
        return success($info);
    }
    public function sendSysMsg(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'msg' => 'required'
        ]);

        $info = User::find($id);

        if(! $this->sendUserMsg(\App\Model\EasemobGroup::SYS_MSG, $request->get('msg'), $info->id)){
            return fail();
        }

        return success();
    }

}
