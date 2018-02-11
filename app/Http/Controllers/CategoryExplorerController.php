<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\CategoryExplorer;

class CategoryExplorerController extends BaseController
{
    public function index(Request $request, $cat_id)
    {
        $list = CategoryExplorer::where('category_id', $cat_id);

        $list = $list->get();

        return success($list);
    }
    public function store(Request $request, $cat_id)
    {
        $validator = \Validator::make($request->all(), [
            'params' => 'required|array',
            'params.*.name' => 'required',
            'params.*.url' => 'required',
            'params.*.sort' => 'numeric',
            'params.*.enable' => 'boolean',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }
        DB::beginTransaction();
        try{
            if(CategoryExplorer::where('category_id', $cat_id)->delete() === false){
                throw new \Exception('创建项目浏览器失败!');
            }
            $params = $request->get('params');
            foreach($params as $param){
                $param['category_id'] = $cat_id;
                if(! CategoryExplorer::create($param)){
                    throw new \Exception('创建项目浏览器失败!');
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return fail();
        }
        return success();
    }
    public function update(Request $request, $cat_id, $cat_exp_id)
    {
        $info = CategoryExplorer::where('category_id', $cat_id)->find($cat_exp_id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $cat_id, $cat_exp_id)
    {
        $info = CategoryExplorer::where('category_id', $cat_id)->find($cat_exp_id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $cat_id, $cat_exp_id)
    {
        $info = CategoryExplorer::where('category_id', $cat_id)->find($cat_exp_id);
        return success($info);
    }

}
