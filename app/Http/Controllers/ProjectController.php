<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Category;
use OSS;

class ProjectController extends BaseController
{
    public function index(Request $request)
    {
        $is_key = $request->has('get_key');
        
        $type = $request->get('type', 0);
        if($is_key){
            $list = Category::select(['id','name','type'])->isEnable()->get();
        } else {
            $list = Category::isProject()->ofType($type)->paginate(5);
        }
        $type = Category::$project_category;
        return success(compact('list', 'type'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'type' => 'required',
                'name' => 'required',
                'grid_type' => 'required',
                'img' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }

        $info = new Category();
        $info->fill($request->all());

        return $info->save() ?  success() :  fail();
    }

    public function show(Request $request, $id)
    {
        $info      = Category::find($id);
        $type      = Category::$project_category;
        $grid_type = Category::$grid_type;
        return success(compact('info', 'type', 'grid_type'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
                'type' => 'required',
                'name' => 'required',
                'grid_type' => 'required',
                'img' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        
        $info = Category::find($id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return Category::find($id)->delete() ? success() : fail();
    }
}