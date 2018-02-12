<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\MenuGroup;

class MenuGroupController extends BaseController
{
    public function index(Request $request)
    {
        $list = MenuGroup::paginate($this->per_page);

        return success($list);
    }
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'group_name' => 'required',
            'enable' => 'boolean',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }
        $info = new MenuGroup();
        $info->fill($request->all());

        return $info->save() ? success($info->toArray()) : fail();
    }
    public function update(Request $request, $id)
    {
        $info = MenuGroup::find($id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $id)
    {
        $info = MenuGroup::find($id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $id)
    {
        $info = MenuGroup::find($id);
        return success($info);
    }

}
