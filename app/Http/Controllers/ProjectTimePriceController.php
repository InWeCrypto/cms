<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\ProjectTimePrice;

class ProjectTimePriceController extends BaseController
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
        $info        = Category::with('ProjectTimePrice')->find($category_id);

        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(ProjectTimePrice::find($id));
    }

    public function store(Request $request)
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
        $info = new ProjectTimePrice();
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
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
        $info = ProjectTimePrice::find($id);
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return ProjectTimePrice::find($id)->delete() ? success() : fail();
    }

}