<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\ProjectWallet;

class ProjectWalletController extends BaseController
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
        $info        = Category::with('ProjectWallet')->find($category_id);

        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(ProjectWallet::find($id));
    }

    public function store(Request $request)
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
        $info = new ProjectWallet();
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
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
        $info = ProjectWallet::find($id);
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return ProjectWallet::find($id)->delete() ? success() : fail();
    }

}