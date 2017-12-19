<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Ad;

class AdController extends BaseController
{

    public function index(Request $request)
    {
        $select = [
                'id',
                'name',
                'img',
                'desc',
                'url',
                'sort',
                'start_at',
                'end_at',
                'enable'
            ];
        $list = Ad::select($select)->paginate($request->get('pre_page', 10));
        return success($list);
    }

    public function show(Request $request, $id)
    {
        return success(Ad::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'img' => 'required',
                'name' => 'required',
                'desc' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = new Ad();
        $info->fill($request->all());
        $info->status = $info->status ?: 1;

        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'img' => 'required',
            'name' => 'required',
            'desc' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = Ad::find($id);
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return Ad::find($id)->delete() ? success() : fail();
    }

}
