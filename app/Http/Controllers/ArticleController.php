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
            $list = $list->where('type', $type);
        }
        if ($lang = $request->get('lang')){
            $list = $list->where('lang', $lang);
        }
        $list = $list->paginate($this->per_page);

        return success($list);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'type' => 'required|numeric',
            'category_id' => 'required|numeric',
            'title' => 'required',
            'content' => 'required',
            'lang' => 'required',
            'desc' => 'required',
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

            if($Article->is_hot || $Article->is_scroll || $Article->category_id == 0){
                $this->sendGroupMsg(EasemobGroup::SYS_MSG_INWEHOT, $Article->title, $Article->lang);
            }
            if($Article->type == Article::TRADING){
                $this->sendGroupMsg(EasemobGroup::SYS_MSG_TRADING, $Article->title, $Article->lang);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return fail();
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
