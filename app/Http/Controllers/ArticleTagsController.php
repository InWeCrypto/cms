<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\ArticleTag;
use App\Model\ArticleTagInfo;
use App\Model\Article;

class ArticleTagsController extends BaseController
{
    public function index(Request $request, $art_id)
    {
        $list = Article::find($art_id)->articleTagInfo()->get();

        return success($list);
    }
    public function store(Request $request, $art_id)
    {
        $validator = \Validator::make($request->all(), [
            'tag_ids' => 'required|array',
        ]);

        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }
        // 获取文章语言
        $article = Article::find($art_id, ['id', 'lang']);
        $lang    = $article ? $article->lang : config('app.locale');

        $tag_ids = $request->get('tag_ids');

        $tag_ids = ArticleTagInfo::where('lang', $lang)->whereIn('id', $tag_ids)->get(['id']);

        DB::beginTransaction();
        try {
            $data = [];
            foreach($tag_ids as $tag_id){
                $temp = [];
                $temp['tag_id'] = $tag_id->id;
                $temp['article_id'] = $article->id;
                $temp['created_at'] = date('Y-m-d H:i:s');
                $temp['updated_at'] = date('Y-m-d H:i:s');

                $data[] = $temp;
            }
            if(ArticleTag::where('article_id',$article->id)->delete() === false){
                throw new \Exception('清空文章标签失败');
            }
            if(! ArticleTag::insert($data)){
                throw new \Exception('添加文章标签失败');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return fail($e->getMessage());
        }

        return success();
    }

}
