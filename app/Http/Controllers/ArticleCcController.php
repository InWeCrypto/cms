<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;
use App\Model\ArticleCategoryCc;

class ArticleCcController extends BaseController
{

    public function index(Request $request)
    {
        $select = [
            'id',
            'title',
        ];
        $validator = \Validator::make($request->all(), [
                'article_id' => 'required',
            ], [
                'article_id.required' => '请传入资讯ID'
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $article_id = $request->get('article_id');
        $info       = Article::select($select)->with('ccCategory.category')->find($article_id);

        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(ArticleCategoryCc::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'article_id' => 'required',
                'category_ids' => 'required'
            ], [
                'article_id.required' => '请传入资讯ID',
                'category_ids.required' => '请传入项目ID'
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        if (! $category_ids = json_decode($request->get('category_ids'), true)) {
            return fail('项目ID为空!', NOT_VALIDATED);
        }
        $article_id = $request->get('article_id');
        try {
            \DB::transaction(function() use ($article_id, $category_ids){
                ArticleCategoryCc::where('article_id', $article_id)->delete();
                $data = [];
                foreach($category_ids as $category_id){
                    $data[] = [
                        'article_id' => $article_id,
                        'category_id' => $category_id
                    ];
                }
                ArticleCategoryCc::insert($data);
            });
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
        return success();
    }

    public function destroy(Request $request, $id){
        return ArticleCategoryCc::find($id)->delete() ? success() : fail();
    }

}