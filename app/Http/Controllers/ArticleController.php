<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;

class ArticleController extends BaseController
{

    public function index(Request $request)
    {
        $select = [
            'id',
            'title',
            'desc',
            'category_id',
            'sort',
            'is_top',
            'is_hot',
            'is_scroll',
            'status',
            'enable',
            'video',
            'click_rate',
            'type'
            ];
        $list = Article::select($select)->ofType(Article::ALL)->paginate(2);
        $type = Article::$type;
        return success(compact('list','type'));
    }

    public function show(Request $request, $id)
    {
        return success(Article::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'category_id' => 'required',
                'type' => 'required',
                'title' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = new Article();
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
                'category_id' => 'required',
                'type' => 'required',
                'title' => 'required',
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