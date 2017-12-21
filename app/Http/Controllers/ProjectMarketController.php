<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\ProjectMarket;

class ProjectMarketController extends BaseController
{

    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'category_id' => 'required',
            ], [
                'category_id.required' => '请传入项目ID'
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $category_id = $request->get('category_id');
        $info        = Category::with('projectMarket')->find($category_id);

        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(ProjectMarket::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'project_id' => 'required',
                'name' => 'required',
            ], [
                'project_id.required' => '请传入项目ID',
                'name.required' => '请输入名称',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = new ProjectMarket();
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'project_id' => 'required',
            'name' => 'required',
            'img' => 'required'
        ], [
            'project_id.required' => '请传入项目ID',
            'name.required' => '请输入名称',
            'name.required' => '请选择图片'
        ]);
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = ProjectMarket::find($id);
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return ProjectMarket::find($id)->delete() ? success() : fail();
    }

}
