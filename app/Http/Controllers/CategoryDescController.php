<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\CategoryDesc;
use \EasemobRoom as ERoom;

class CategoryDescController extends BaseController
{
    public function index(Request $request, $cat_id)
    {
        $list = CategoryDesc::where('category_id', $cat_id);

        if($lang = $request->get('lang')){
            $list = $list->where('lang', $lang);
        }

        $list = $list->get();

        return success($list);
    }
    public function store(Request $request, $cat_id)
    {
        $validator = \Validator::make($request->all(), [
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'content' => 'required',
            'lang' => 'required',
            'enable' => 'boolean',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

        $info = CategoryDesc::where([
            'category_id' => $cat_id,
            'lang' => $request->get('lang', config('app.locale'))
        ])->first() ?: new CategoryDesc();

        $info->fill($request->all());
        $info->category_id = $cat_id;

        return $info->save() ? success() : fail();
    }
    public function update(Request $request, $cat_id, $cat_desc_id)
    {
        $info = CategoryDesc::where('category_id', $cat_id)->find($cat_desc_id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $cat_id, $cat_desc_id)
    {
        $info = CategoryDesc::where('category_id', $cat_id)->find($cat_desc_id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $cat_id, $cat_desc_id)
    {
        $info = CategoryDesc::where('category_id', $cat_id)->find($cat_desc_id);
        return success($info);
    }

}
