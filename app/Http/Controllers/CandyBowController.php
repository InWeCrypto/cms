<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\CandyBow;

class CandyBowController extends BaseController
{
    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'year' => 'numeric|required_with:month|min:1970',
            'month' => 'numeric|required_with:day|between:1,12',
            'day' => 'numeric|between:1,31'
        ]);

        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

        $list = CandyBow::with('category');

        if($category_id = $request->get('category_id')){
            $list = $list->where('category_id', $category_id);
        }
        if($lang = $request->get('lang')){
            $list = $list->where('lang', $lang);
        }

        if($year = $request->get('year')){
            $list = $list->where('year', $year);
        }
        if($month = $request->get('month')){
            $list = $list->where('month', $month);
        }
        if($day = $request->get('day')){
            $list = $list->where('day', $day);
        }

        $list = $list->paginate($this->per_page);

        return success($list);
    }
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'category_id' => 'required',
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

        // $info->category_id = $cat_id;

        $msg = $info->year . '-' . $info->month . '-' . $info->day . ' 有项目进行空投';

        // $this->sendGroupMsg(EasemobGroup::SYS_MSG_CANDYBOW, $msg, $info->lang);

        return $info->save() ? success() : fail();
    }
    public function update(Request $request, $id)
    {
        $info = CandyBow::find($id);

        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $id)
    {
        $info = CandyBow::find($id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $id)
    {
        $info = CandyBow::find($id);
        return success($info);
    }

}
