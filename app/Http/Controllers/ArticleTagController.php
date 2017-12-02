<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Article;
use App\Model\ArticleTag;

class ArticleTagController extends BaseController
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
        $info        = Article::select($select)->with('articleTag.tagInfo')->find($article_id);

        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(ArticleTag::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'article_id' => 'required',
                'tag_ids' => 'required'
            ], [
                'article_id.required' => '请传入资讯ID',
                'tag_ids.required' => '请传入标签ID'
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        if (! $tag_ids = json_decode($request->get('tag_ids'), true)) {
            return fail('标签ID为空!', NOT_VALIDATED);
        }
        $article_id = $request->get('article_id');
        try {
            \DB::transaction(function() use ($article_id, $tag_ids){
                ArticleTag::where('article_id', $article_id)->delete();
                $data = [];
                foreach($tag_ids as $tag_id){
                    $data[] = [
                        'article_id' => $article_id,
                        'tag_id' => $tag_id
                    ];
                }
                ArticleTag::insert($data);
            });
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
        return success();
    }

    public function destroy(Request $request, $id){
        return ArticleTag::find($id)->delete() ? success() : fail();
    }

}