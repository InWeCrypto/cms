<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\ExchangeNotice;
use App\Model\EasemobGroup;

class ExchangeNoticeController extends BaseController
{
    public function index(Request $request)
    {
        if($request->has('getKeys')){
            return success($this->getExchangeList());
        }

        $list = ExchangeNotice::whereRaw('1=1');

        if($source_name = $request->get('source_name')){
            $list->where('source_name', $source_name);
        }

        if($keyword = $request->get('keyword')){
            $list->where(function($query) use($keyword){
                $keyword = '%'.$keyword.'%';
                $query->orWhere('desc', 'like', $keyword);
                $query->orWhere('content', 'like', $keyword);
            });
        }

        $list = $list->paginate($this->per_page);

        return success($list);
    }
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'source_name' => 'required',
            'source_url' => 'required',
            'content' => 'required',
            'lang' => 'required',
            'is_hot' => 'boolean',
            'is_scroll' => 'boolean',
            'is_top' => 'boolean',
            'enable' => 'boolean',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

        $info = new ExchangeNotice();

        $info->fill($request->all());

        $msg = $info->source_name . ':' . $info->content;

        if($info->save()){
            if($request->get('send_app_message')){
                $this->sendGroupMsg(EasemobGroup::SYS_MSG_EXCHANGENOTICE, $msg, $info->lang);
            }
            return success();
        }else {
            return fail();
        }

    }
    public function update(Request $request, $id)
    {
        $info = ExchangeNotice::find($id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $id)
    {
        $info = ExchangeNotice::find($id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $id)
    {
        $info = ExchangeNotice::find($id)->makeVisible('content');
        return success($info);
    }
    // 获取交易所列表
    public function getExchangeList()
    {
        $select =<<<EOT
select author
from articles
where type=16 and author != ''
group by author
EOT;
        $return = [];
        foreach(DB::select($select) as $li){
            $return[] = $li->author;
        }

        return $return;
    }

}
