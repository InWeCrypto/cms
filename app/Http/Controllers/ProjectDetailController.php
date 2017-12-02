<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\Article;

class ProjectDetailController extends BaseController
{

    public function index(Request $request)
    {
        \OSS::list('imgs/');
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
        $info        = Category::with(['projectDetail'=>function($query){
            return $query->select(['id','title','sort','category_id'])->where('type', \App\Model\Article::PROJECT_DESC);
        }])->find($category_id);

        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(Article::find($id));
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
        $request->offsetSet('type', Article::PROJECT_DESC);
        $info = new Article();
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
        $info = Article::find($id);
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return Article::find($id)->delete() ? success() : fail();
    }

}