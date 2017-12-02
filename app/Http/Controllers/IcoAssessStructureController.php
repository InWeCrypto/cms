<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\IcoAssessArticle;
use App\Model\IcoAssessStructure;

class IcoAssessStructureController extends BaseController
{

    public function index(Request $request)
    {
        $ico_assess_id = $request->get('ico_assess_id', 0);
        $select        = [
            'id',
            'project_id',
            'assess_status',
            'website',
            'url',
            'title',
            'img',
            'ico_id',
        ];
        $info = IcoAssessArticle::select($select)
                    ->with('icoAssessStructure')
                    ->find($ico_assess_id);
        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(IcoAssessStructure::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'ico_article_id' => 'required',
                'percentage' => 'required',
                'color_name' => 'required',
                'color_value' => 'required',
                'desc' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = new IcoAssessStructure();
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
                'ico_article_id' => 'required',
                'percentage' => 'required',
                'color_name' => 'required',
                'color_value' => 'required',
                'desc' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = IcoAssessStructure::find($id);
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return IcoAssessStructure::find($id)->delete() ? success() : fail();
    }

}