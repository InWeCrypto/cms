<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\ArticleCategoryCc;
use App\Model\Article;

class ArticleCcController extends BaseController
{
    public function index(Request $request, $art_id)
    {
        $list = Article::find($art_id)->articleCategoryCc()->with('category')->get();

        return success($list);
    }
    public function store(Request $request, $art_id)
    {
        $validator = \Validator::make($request->all(), [
            'category_ids' => 'required|array',
        ]);

        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

        $article = Article::find($art_id);

        $category_ids= $request->get('category_ids');
        $category_ids = array_unique(array_diff($category_ids, [$article->category_id]));

        DB::beginTransaction();
        try {
            $data = [];
            foreach($category_ids as $category_id){
                $temp = [];
                $temp['category_id'] = $category_id;
                $temp['article_id'] = $article->id;
                $temp['created_at'] = date('Y-m-d H:i:s');
                $temp['updated_at'] = date('Y-m-d H:i:s');

                $data[] = $temp;
            }
            if(ArticleCategoryCc::where('article_id',$article->id)->delete() === false){
                throw new \Exception('清空文章关联项目失败');
            }
            if(! ArticleCategoryCc::insert($data)){
                throw new \Exception('添加文章关联项目失败');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return fail($e->getMessage());
        }

        return success();
    }

}
