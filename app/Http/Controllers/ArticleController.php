<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Article;
use App\Model\CategoryUser;
use App\Model\EasemobGroup;

class ArticleController extends BaseController
{
    public function index(Request $request)
    {
        $list = Article::whereRaw('1=1');
        if ($type = $request->get('type')){
            $types = json_decode($type, true);
            if(is_array($types)){
                $list = $list->whereIn('type', $types);
            }else{
                $list = $list->where('type', $type);
            }
        }else{
            $list = $list->whereIn('type', [1,2,3,6]);
        }
        if ($category_id = $request->get('category_id')){
            $list = $list->where('category_id', $category_id);
        }else{
            if($request->has('is_category')){
                $list = $list->where('category_id', '=', '0');
            }else{
                $list = $list->where('category_id', '!=', '0');
            }
        }
        if ($lang = $request->get('lang')){
            $list = $list->where('lang', $lang);
        }
        if ($keyword = $request->get('keyword')){
            $list = $list->where(function($query) use ($keyword){
                $k = '%' . strtoupper($keyword) . '%';
                $query->whereRaw("UPPER(title) like '{$k}'");
                if(is_numeric($keyword)){
                    $query->orWhere('id', $keyword);
                }
            });
        }
        if ($author = $request->get('author')){
            $list = $list->where('author', $author);
        }
        if (is_numeric($request->get('is_scroll'))){
            $list = $list->where('is_scroll', $request->get('is_scroll'));
        }
        if (is_numeric($request->get('is_sole'))){
            $list = $list->where('is_sole', $request->get('is_sole'));
        }
        $list = $list->with(['category','articleTag'])->paginate($this->per_page);

        return success($list);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'type' => 'required|numeric',
            'category_id' => 'required|numeric',
            'title' => 'required',
            'content' => 'required_unless:type,6,3,12',
            'url'=> 'required_if:type,6',
            'lang' => 'required',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }
        DB::beginTransaction();
        try{

            $Article = new Article();
            $Article->fill($request->all());

            // 用户收藏项目小红点
            $update_stat = CategoryUser::where('category_id', $Article->category_id)
                            ->where('is_favorite', 1)
                            ->update(['is_favorite_dot' => 1]);

            if(!$Article->save() || $update_stat === false){
                throw new \Exception(trans('custom.FAIL'), FAIL);
            }
            if($request->get('send_app_message')){
                if(in_array($Article->type ,[
                        Article::TRADING,
                        Article::TRADING_VIDEO
                    ])){
                        $this->sendGroupMsg(EasemobGroup::SYS_MSG_TRADING, $Article->title, $Article->lang);
                }else if(in_array($Article->type, [
                        Article::TXT,
                        Article::IMG,
                        Article::VIDEO,
                        Article::FILE,
                    ]) && $Article->category_id == 0){
                    $this->sendGroupMsg(EasemobGroup::SYS_MSG_INWEHOT, $Article->title, $Article->lang);
                }else if(in_array($Article->type, [
                        Article::VIEWPOINT_TXT,
                        Article::VIEWPOINT_IMG,
                        Article::VIEWPOINT_VIDEO,
                        Article::VIEWPOINT_FILE,
                    ])){
                    $this->sendGroupMsg(EasemobGroup::SYS_MSG_VIEWPOINT, $Article->title, $Article->lang);
                }
            }
            DB::commit();
            return success($Article->toArray());
        } catch (\Exception $e) {
            DB::rollBack();
            return fail($e->getMessage());
        }

        return success();
    }
    public function update(Request $request, $cid)
    {
        $Article = Article::find($cid);
        $Article->fill($request->all());

        return $Article->save() ? success() : fail();
    }
    public function destroy(Request $request, $cid)
    {
        $Article = Article::find($cid);
        // 如果是交易所公告类型,修改爬虫发布状态
        if($Article->type == Article::EXCHANGE_NOTICE){
            \App\Model\ExchangeNoticeTemp::where('article_id', $cid)->update(['article_id'=>'0']);
        }
        return $Article->delete() ? success() : fail();
    }
    public function show(Request $request, $cid)
    {
        $info = Article::find($cid)->makeVisible('content');
        return success($info);
    }
    public function getTypes(Request $request)
    {
        return success(Article::$types);
    }

}
