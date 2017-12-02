<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\IcoAssessArticle;
use App\Model\icoAssessIssueInfo;

class IcoAssessIssueInfoController extends BaseController
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
            'desc',
            'enable',
            'risk_level_name',
            'risk_level_color',
            'ico_score',
            'ico_id',
            'white_paper_url',
            'recommend_level_name',
            'recommend_level_color',
            'is_top',
            'is_hot',
            'is_scroll',
            'status',
            'sort',
            'enable'
        ];
        $info = IcoAssessArticle::select($select)
                    ->with('icoAssessIssueInfo')
                    ->find($ico_assess_id);
        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(icoAssessIssueInfo::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'ico_article_id' => 'required',
                'ico_circulation' => 'required',
                'ico_amount' => 'required',
                'ico_accept' => 'required',
                'ico_crowfunding_amount' => 'required',
                'ico_price' => 'required',
                'url' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = new icoAssessIssueInfo();
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
                'ico_article_id' => 'required',
                'ico_circulation' => 'required',
                'ico_amount' => 'required',
                'ico_accept' => 'required',
                'ico_crowfunding_amount' => 'required',
                'ico_price' => 'required',
                'url' => 'required',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = icoAssessIssueInfo::find($id);
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return icoAssessIssueInfo::find($id)->delete() ? success() : fail();
    }

}