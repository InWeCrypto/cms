<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\ProjectTag;

class ProjectTagController extends BaseController
{

    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'category_id' => 'required',
            ], [
                'category_id.required' => '请传入项目ID'
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $category_id = $request->get('category_id');
        $info        = Category::with('projectTag.tagInfo')->find($category_id);

        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(ProjectTag::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'category_id' => 'required',
                'tag_ids' => 'required'
            ], [
                'category_id.required' => '请传入项目ID',
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
        $category_id = $request->get('category_id');
        try {
            \DB::transaction(function() use ($category_id, $tag_ids){
                ProjectTag::where('project_id', $category_id)->delete();
                $data = [];
                foreach($tag_ids as $tag_id){
                    $data[] = [
                        'project_id' => $category_id,
                        'tag_id' => $tag_id
                    ];
                }
                ProjectTag::insert($data);
            });
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
        return success();
    }

    // public function update(Request $request, $id)
    // {
    //     $validator = \Validator::make($request->all(), [
    //             'category_id' => 'required',
    //         ], [
    //             'category_id.required' => '请传入项目ID'
    //         ]
    //     );
    //     if ($validator->fails()){
    //         return fail($validator->messages()->first(), NOT_VALIDATED);
    //     }
    //     $info = ProjectTag::find($id);
    //     $info->fill($request->all());
    //     return $info->save() ? success() : fail();
    // }

    public function destroy(Request $request, $id){
        return ProjectTag::find($id)->delete() ? success() : fail();
    }

}