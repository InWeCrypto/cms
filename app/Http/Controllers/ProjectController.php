<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Category;
use OSS;

class ProjectController extends BaseController
{
    public function index(Request $request)
    {
        $keyword = trim($request->get('keyword',''));
        $is_key  = $request->has('get_key');

        $type = $request->get('type', 0);
        if($is_key){
            $list = Category::select(['id','name','type'])
                    ->isEnable()
                    ->where(function($query) use ($keyword) {
                        $query->orWhere('name', 'like', '%'.$keyword.'%')
                              ->orWhere('long_name', 'like', '%'.$keyword.'%');
                    })
                    ->get();
        } else {
            $list = Category::isProject()
                    ->ofType($type)
                    ->where(function($query) use ($keyword) {
                        $query->orWhere('name', 'like', '%'.$keyword.'%')
                              ->orWhere('long_name', 'like', '%'.$keyword.'%');
                    })
                    ->paginate(5);
        }
        $type = Category::$project_category;
        $grid_type = Category::$grid_type;
        return success(compact('list', 'type', 'grid_type'));
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
        $info->p_id = $info->p_id ?: CAT_HOME_PROJECT;
        $info->callback_fun = $info->callback_fun ?: '\CategoryFun::getProjectDetail';
        $info->status = $info->status ?: 1;
        $info->grid_type = $info->grid_type ?: 1;
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

    public function getType(Request $request, $id = null)
    {
        $data = Category::$project_category;
        if($id){
            if(empty($data[$id])){
                return fail('不存在!');
            }
            $data = $data[$id];
        }
        return success($data);
    }

    public function getGridType(Request $request, $id = null)
    {
        $data = Category::$grid_type;
        if($id){
            if(empty($data[$id])){
                return fail('不存在!');
            }
            $data = $data[$id];
        }
        return success($data);
    }
}
