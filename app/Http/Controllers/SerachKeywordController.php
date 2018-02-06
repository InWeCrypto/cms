<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\SerachKeyword;

class SerachKeywordController extends BaseController
{
    public function index(Request $request)
    {
        $list = SerachKeyword::whereRaw('1=1');

        if ($lang = $request->get('lang')){
            $list = $list->where('lang', $lang);
        }
        $list = $list->paginate($this->per_page);

        return success($list);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'lang' => 'required',
            'enable' => 'boolean',
        ]);

        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }


        $SerachKeyword = new SerachKeyword();
        $SerachKeyword->fill($request->all());


        return $SerachKeyword->save() ? success() : fail();
    }
    public function update(Request $request, $id)
    {
        $SerachKeyword = SerachKeyword::find($id);
        $SerachKeyword->fill($request->all());

        return $SerachKeyword->save() ? success() : fail();
    }
    public function destroy(Request $request, $id)
    {
        $SerachKeyword = SerachKeyword::find($id);
        return $SerachKeyword->delete() ? success() : fail();
    }
    public function show(Request $request, $id)
    {
        $info = SerachKeyword::find($id);
        return success($info);
    }

}
