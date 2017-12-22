<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\ProjectExplorer;

class ProjectExplorerController extends BaseController
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
        $info        = Category::with('projectExplorer')->find($category_id);

        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(ProjectExplorer::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'project_id' => 'required',
            'name' => 'required',
            'img' => 'required'
        ], [
            'project_id.required' => '请传入项目ID',
            'name.required' => '请输入名称',
            'img.required' => '请选择图片'
        ]);
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = new ProjectExplorer();
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
            'img.required' => '请选择图片'
        ]);
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = ProjectExplorer::find($id);
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return ProjectExplorer::find($id)->delete() ? success() : fail();
    }

}
