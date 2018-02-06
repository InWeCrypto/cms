<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\CategoryMedia;

class CategoryMediaController extends BaseController
{
    public function index(Request $request, $cat_id)
    {
        $list = CategoryMedia::where('category_id', $cat_id);

        $list = $list->get();

        return success($list);
    }
    public function store(Request $request, $cat_id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'url' => 'required',
            'img' => 'required',
            'sort' => 'numeric',
            'enable' => 'boolean',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

        $info = new CategoryMedia();

        $info->fill($request->all());
        $info->category_id = $cat_id;

        return $info->save() ? success() : fail();
    }
    public function update(Request $request, $cat_id, $cat_media_id)
    {
        $info = CategoryMedia::where('category_id', $cat_id)->find($cat_media_id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $cat_id, $cat_media_id)
    {
        $info = CategoryMedia::where('category_id', $cat_id)->find($cat_media_id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $cat_id, $cat_media_id)
    {
        $info = CategoryMedia::where('category_id', $cat_id)->find($cat_media_id);
        return success($info);
    }

}
