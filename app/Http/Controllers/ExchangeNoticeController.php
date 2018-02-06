<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\ExchangeNotice;

class ExchangeNoticeController extends BaseController
{
    public function index(Request $request)
    {
        $list = ExchangeNotice::paginate($this->per_page);

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

        $this->sendGroupMsg(EasemobGroup::SYS_MSG_INWEHOT, $msg, $info->lang);

        return $info->save() ? success() : fail();
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

}
