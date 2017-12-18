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
            'img',
            'sort',
            'is_top',
            'is_hot',
            'is_scroll',
            'status',
            'enable',
            'video',
            'click_rate',
            'created_at',
            'updated_at',
            'type'
            ];
        $list = Article::select($select)->ofType(Article::ALL)->paginate(10);
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
        $info->status = $info->status ?: 1;
        // 如果是快讯默认放在24h news里
        if($info->type == Article::TXT){
            $info->is_scroll = 1;
        }
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
