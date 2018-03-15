<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\ExchangeNoticeTemp;
use App\Model\ExchangeNotice;

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
                'lang' => $li['lang']
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
        $data = [];
        $data['source_name'] = $temp->source;
        $data['lang'] = $temp->lang;
        $data['desc'] = $temp->article_title;
        $data['content'] = $temp->article_content;
        $data['source_url'] = $temp->uri;
        return ExchangeNotice::create($data) ? success() : fail();
    }
}
