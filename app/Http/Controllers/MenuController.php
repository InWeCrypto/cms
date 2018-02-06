<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Menu;

class MenuController extends BaseController
{
    public function index(Request $request)
    {
        $list = Menu::paginate($this->per_page);

        return success($list);
    }
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'url' => 'required',
            'enable' => 'boolean',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }
        $info = new Menu();
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function update(Request $request, $id)
    {
        $info = Menu::find($id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $id)
    {
        $info = Menu::find($id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $id)
    {
        $info = Menu::find($id);
        return success($info);
    }

}
