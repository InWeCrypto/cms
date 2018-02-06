<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\CategoryStructure;

class CategoryStructureController extends BaseController
{
    public function index(Request $request, $cat_id)
    {
        $list = CategoryStructure::where('category_id', $cat_id);

        $list = $list->get();

        return success($list);
    }
    public function store(Request $request, $cat_id)
    {
        $validator = \Validator::make($request->all(), [
            'percentage' => 'required|numeric',
            'color_value' => 'required',
            'desc' => 'required',
            'lang' => 'required',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

        $info = new CategoryStructure();

        $info->fill($request->all());
        $info->category_id = $cat_id;

        return $info->save() ? success() : fail();
    }
    public function update(Request $request, $cat_id, $cat_stru_id)
    {
        $info = CategoryStructure::where('category_id', $cat_id)->find($cat_stru_id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $cat_id, $cat_stru_id)
    {
        $info = CategoryStructure::where('category_id', $cat_id)->find($cat_stru_id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $cat_id, $cat_stru_id)
    {
        $info = CategoryStructure::where('category_id', $cat_id)->find($cat_stru_id);
        return success($info);
    }

}
