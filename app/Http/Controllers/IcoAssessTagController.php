<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\IcoAssessArticle;
use App\Model\IcoAssessArticleTag;

class IcoAssessTagController extends BaseController
{

    public function index(Request $request)
    {
        $select = [
            'id',
            'title',
            'desc',
            'sort',
            'is_top',
            'is_hot',
            'is_scroll',
            'status',
            'enable',
            // 'click_rate',
            'ico_id'
        ];
        $validator = \Validator::make($request->all(), [
                'ico_assess_id' => 'required',
            ], [
                'ico_assess_id.required' => '请传入资讯ID'
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $ico_assess_id = $request->get('ico_assess_id');
        $info          = IcoAssessArticle::select($select)->with('tags.tagInfo')->find($ico_assess_id);

        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(IcoAssessArticleTag::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'ico_assess_id' => 'required',
                'tag_ids' => 'required'
            ], [
                'ico_assess_id.required' => '请传入测评ID',
                'tag_ids.required' => '请传入标签ID'
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }

        if (! $tag_ids = json_decode($request->get('tag_ids'), true)) {
            return fail('标签ID为空!', NOT_VALIDATED);
        }
        $tag_ids = array_unique($tag_ids);
        $ico_assess_id = $request->get('ico_assess_id');
        try {
            \DB::transaction(function() use ($ico_assess_id, $tag_ids){
                IcoAssessArticleTag::where('ico_assess_id', $ico_assess_id)->delete();
                $data = [];
                foreach($tag_ids as $tag_id){
                    $data[] = [
                        'ico_assess_id' => $ico_assess_id,
                        'tag_id' => $tag_id
                    ];
                }
                IcoAssessArticleTag::insert($data);
            });
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
        return success();
    }

    public function destroy(Request $request, $id){
        return IcoAssessArticleTag::find($id)->delete() ? success() : fail();
    }

}