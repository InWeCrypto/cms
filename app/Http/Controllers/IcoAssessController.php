<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\IcoAssessArticle;

class IcoAssessController extends BaseController
{

    public function index(Request $request)
    {
        $select        = [
            'id',
            'project_id',
            'assess_status',
            'website',
            'url',
            'title',
            'img',
            'desc',
            'enable',
            'risk_level_name',
            'risk_level_color',
            'ico_score',
            'ico_id',
            'white_paper_url',
            'recommend_level_name',
            'recommend_level_color',
        ];
        $info = IcoAssessArticle::select($select)->paginate(5);
        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(IcoAssessArticle::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'project_id' => 'required',
                'title' => 'required',
                'ico_score' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = new IcoAssessArticle();
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
                'project_id' => 'required',
                'title' => 'required',
                'ico_score' => 'required',
            ]
        );
        $info = IcoAssessArticle::find($id);
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return IcoAssessArticle::find($id)->delete() ? success() : fail();
    }

}