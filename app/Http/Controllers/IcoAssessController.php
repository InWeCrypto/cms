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
            'ico_score',
            'ico_id',
            'white_paper_url',
            'risk_level_id',
            'recommend_level_id'
        ];
        $info = IcoAssessArticle::select($select)->with('riskLevelInfo', 'recommendLevelinfo')->paginate(5);
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
        $info->status = $info->status ?: 1;
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
