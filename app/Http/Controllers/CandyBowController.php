<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\CandyBow;

class CandyBowController extends BaseController
{

    public function index(Request $request)
    {

        $info       = CandyBow::whereRaw('1=1');
        if($project_id = $request->get('category_id')){
            $info->where('project_id', $project_id);
        }
        if($year = $request->get('year')){
            $info->where('year', $year);
        }
        if($month = $request->get('month')){
            if(!$year){
                return fail('月份必须和年份一起!');
            }
            $info->where('year', $year);
        }
        if($day = $request->get('day')){
            if(!$year || !$month){
                return fail('天必须和月份、年份一起!');
            }
            $info->where('day', $day);
        }

        return success($info->paginate($request->get('per_page', 10)));
    }

    public function show(Request $request, $id)
    {
        return success(CandyBow::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'project_id' => 'required',
                'name' => 'required',
                'year' => 'required|integer',
                'month' => 'required|integer',
                'day' => 'required|integer',
            ], [
                'project_id.required' => '请传入项目ID',
                'name.required' => '请填写名称',
                'year.required' => '请填写 年',
                'month.required' => '请填写 月',
                'day.required' => '请填写 日',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = new CandyBow();
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
                'project_id' => 'required',
                'name' => 'required',
                'year' => 'required|integer',
                'month' => 'required|integer',
                'day' => 'required|integer',
            ], [
                'project_id.required' => '请传入项目ID',
                'name.required' => '请填写名称',
                'year.required' => '请填写 年',
                'month.required' => '请填写 月',
                'day.required' => '请填写 日',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = CandyBow::find($id);
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return CandyBow::find($id)->delete() ? success() : fail();
    }

}
