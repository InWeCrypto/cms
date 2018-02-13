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

        if($lang = $request->get('lang')){
            $list = $list->where('lang', $lang);
        }

        $list = $list->get();

        return success($list);
    }
    public function store(Request $request, $cat_id)
    {
        $validator = \Validator::make($request->all(), [
            'params' => 'required|array',
            'params.*.percentage' => 'required|numeric',
            'params.*.color_value' => 'required',
            'params.*.color_name' => 'required',
            'params.*.desc' => 'required',
            'params.*.lang' => 'required',
            'params.*.enable' => 'boolean',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }
        DB::beginTransaction();
        try{
            if(CategoryStructure::where('category_id', $cat_id)->where('lang', $request->get('params.0.lang'))->delete() === false){
                throw new \Exception('创建项目结构失败!');
            }
            $params = $request->get('params');
            foreach($params as $param){
                $param['category_id'] = $cat_id;
                if(! CategoryStructure::create($param)){
                    throw new \Exception('创建项目结构失败!');
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return fail();
        }
        return success();
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
