<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Ico;

class IcoController extends BaseController
{
    public function index(Request $request)
    {
        $select = [
            'id',
            'name',
            'key',
            'long_name',
            'en_name',
            'en_long_name',
            'unit',
            'img',
            'desc',
            'web_site',
        ];
        $is_get_key = $request->has('get_key');
        if($is_get_key){
            $info = Ico::select($select)->get();
        }else{
            $info = Ico::paginate($request->get('per_page', 10));
        }
        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(Ico::find($id));
    }

    public function store(Request $request)
    {
        $info = new Ico();
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
        $info = Ico::find($id);
        $info->fill($request->all());
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return Ico::find($id)->delete() ? success() : fail();
    }

}
