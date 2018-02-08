<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\CandyBow;

class CandyBowController extends BaseController
{
    public function index(Request $request, $cat_id)
    {
        $list = CandyBow::where('category_id', $cat_id);
        if($lang = $request->get('lang')){
            $list = $list->where('lang', $lang);
        }
        $list = $list->paginate($this->per_page);

        return success($list);
    }
    public function store(Request $request, $cat_id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'desc' => 'required',
            'year' => 'required|numeric|min:1970',
            'month' => 'required|numeric|between:1,12',
            'day' => 'required|numeric|between:1,31',
            'lang' => 'required',
            'enable' => 'boolean',
            'is_scroll' => 'boolean',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

        $info = new CandyBow();

        $info->fill($request->all());

        $info->category_id = $cat_id;

        $msg = $info->year . '-' . $info->month . '-' . $info->day . ' 有项目进行空投';

        // $this->sendGroupMsg(EasemobGroup::SYS_MSG_CANDYBOW, $msg, $info->lang);

        return $info->save() ? success() : fail();
    }
    public function update(Request $request, $cat_id, $id)
    {
        $info = CandyBow::where('category_id', $cat_id)->find($id);

        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $cat_id, $id)
    {
        $info = CandyBow::where('category_id', $cat_id)->find($id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $cat_id, $id)
    {
        $info = CandyBow::where('category_id', $cat_id)->find($id);
        return success($info);
    }

}
