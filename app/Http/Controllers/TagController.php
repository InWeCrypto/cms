<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Tag;

class TagController extends BaseController
{
    public function index(Request $request)
    {
        $is_key = $request->has('get_key');

        if($is_key){
            $list = Tag::select(['id','name'])->get();
        } else {
            $list = Tag::paginate($request->get('per_page', 10));
        }
        return success($list);
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

        $info = new Tag();
        $info->fill($request->all());

        return $info->save() ?  success() :  fail();
    }

    public function show(Request $request, $id)
    {
        return success(Tag::find($id));
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

        $info = Tag::find($id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return Tag::find($id)->delete() ? success() : fail();
    }
}
