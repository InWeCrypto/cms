<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\IcoAssessLevelTag;

class IcoAssessLevelTagController extends BaseController
{

    public function index(Request $request)
    {
        $is_key   = $request->has('get_key');

        if($is_key){
            $list = IcoAssessLevelTag::select(['id','name']);
            $request->get('type') && $list->where('type', $request->get('type'));

            $return = [];
            foreach ($list->get() as $v) {
                $return[$v->id] = $v->name;
            }

            return success($return);
        } else {
            $list = IcoAssessLevelTag::paginate(5);
            $type = IcoAssessLevelTag::$type;
            return success(compact('list', 'type'));
        }

    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'name' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }

        $info = new IcoAssessLevelTag();
        $info->fill($request->all());

        return $info->save() ?  success() :  fail();
    }

    public function show(Request $request, $id)
    {
        return success(IcoAssessLevelTag::find($id));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
                'name' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }

        $info = IcoAssessLevelTag::find($id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return IcoAssessLevelTag::find($id)->delete() ? success() : fail();
    }

}
