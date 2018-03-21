<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\ExchangeNoticeTemp;
use App\Model\Article;

use ExNoticeApi;

class ExchangeNoticeSpiderController extends BaseController
{
    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'key' => 'required',
            'lang' => 'required|in:en,zh'
        ]);

        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

        $key = $request->get('key');
        $lang = $request->get('lang');
        $page = $request->get('page');
        $data = ExNoticeApi::getData($key, $lang, $page);
        $return = [
            'prev_page' => $data['prev_page'],
            'next_page' => $data['next_page']
        ];
        foreach($data['data'] as $li){
            $return['list'][] = [
                'id' => $li['id'],
                'source' =>  $li['source'],
                'uri' => $li['uri'],
                'article_date' => $li['article_date'],
                'article_title' => $li['article_title'],
                'created_at' => $li['created_at'],
                'lang' => $li['lang'],
                'article_id' => $li['article_id'],
            ];
        }
        return success($return);
    }

    public function keys(Request $request)
    {
        return success(ExNoticeApi::getExNotices());
    }

    public function show(Request $request, $id)
    {
        return ExchangeNoticeTemp::find($id);
    }

    public function stroe(Request $request, $id)
    {
        $temp = ExchangeNoticeTemp::find($id);
        $data = [
            'type' => Article::EXCHANGE_NOTICE,
            'category_id' => 0
        ];
        $data['title'] = $temp->article_title; // 标题
        $data['lang'] = $temp->lang;  // 语言
        $data['author'] = $temp->source; // 备注
        $data['desc'] = $temp->source; // 备注
        $data['content'] = $temp->article_content; // 内容
        $data['source_url'] = $temp->uri; // 链接

        DB::beginTransaction();
        try {
            $article = Article::create($data);
            if($article){
                $temp->article_id = $article->id;
                if(!$temp->save()){
                    throw new \Exception('回写交易所爬虫数据失败!');
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
        return success();
    }
}
