<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Ad;

class AdsController extends BaseController
{
    public function index(Request $request)
    {
        $list = Ad::paginate($this->per_page);

        return success($list);
    }
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'img' => 'required',
            'url' => 'required',
            'lang' => 'required',
            'sort' => 'numeric',
            'enable' => 'boolean',
            'type' => 'required|integer|in:1,2'
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

        $info = new Ad();

        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function update(Request $request, $ad_id)
    {
        $info = Ad::find($ad_id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $ad_id)
    {
        $info = Ad::find($ad_id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $ad_id)
    {
        $info = Ad::find($ad_id);
        return success($info);
    }

}
