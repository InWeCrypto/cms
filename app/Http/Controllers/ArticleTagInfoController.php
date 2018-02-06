<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\ArticleTagInfo;

class ArticleTagInfoController extends BaseController
{
    public function index(Request $request)
    {
        $list = ArticleTagInfo::whereRaw('1=1');

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
            'sort' => 'numeric',
            'enable' => 'boolean',
        ]);

        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }


        $ArticleTagInfo = new ArticleTagInfo();
        $ArticleTagInfo->fill($request->all());


        return $ArticleTagInfo->save() ? success() : fail();
    }
    public function update(Request $request, $cid)
    {
        $ArticleTagInfo = ArticleTagInfo::find($cid);
        $ArticleTagInfo->fill($request->all());

        return $ArticleTagInfo->save() ? success() : fail();
    }
    public function destroy(Request $request, $cid)
    {
        $ArticleTagInfo = ArticleTagInfo::find($cid);
        return $ArticleTagInfo->delete() ? success() : fail();
    }
    public function show(Request $request, $cid)
    {
        $info = ArticleTagInfo::find($cid);
        return success($info);
    }
    public function getTypes(Request $request)
    {
        return success(ArticleTagInfo::$types);
    }

}
