<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Category;
use \EasemobRoom as ERoom;

class CategoryController extends BaseController
{
    public function index(Request $request)
    {
        $list = Category::whereRaw('1=1');
        if ($type = $request->get('type')){
            $list = $list->where('type', $type);
        }

        if($keyword = $request->get('keyword')){
            $list = $list->where(function($query) use($keyword){
                $query->orWhere('name', 'like', '%'.$keyword.'%');
                $query->orWhere('long_name', 'like', '%'.$keyword.'%');
                $query->orWhere('unit', 'like', '%'.strtoupper($keyword).'%');
            });
        }

        if($request->has('getKeys')){
            $list = $list->pluck('name','id');
        }else{
            $list = $list->paginate($this->per_page);
        }


        return success($list);
    }
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'type' => 'required|numeric',
            'name' => 'required',
            'long_name' => 'required',
            'unit' => 'required',
            'token_holder' => 'required',
            'img' => 'required',
            'sort' => 'numeric',
            'is_hot' => 'boolean',
            'is_top' => 'boolean',
            'is_scroll' => 'boolean',
            'enable' => 'boolean',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }
        $Category = new Category();
        $Category->fill($request->all());

        $room = strtoupper($Category->name);
        if (! $room_id = ERoom::create($room, $room, 'admin')) {
            return fail(trans('custom.FAIL'), API_ADD_ROOM_FAIL);
        }

        $Category->room_id = $room_id;
        return $Category->save() ? success($Category->toArray()) : fail();
    }
    public function update(Request $request, $cid)
    {
        $Category = Category::find($cid);
        $Category->fill($request->all());

        return $Category->save() ? success() : fail();
    }
    public function destroy(Request $request, $cid)
    {
        $Category = Category::find($cid);

        if(! ERoom::remove($Category->room_id)){
            return fail(trans('custom.FAIL'), API_REMOVE_ROOM_FAIL);
        }

        return $Category->delete() ? success() : fail();
    }
    public function show(Request $request, $cid)
    {
        $info = Category::find($cid);
        return success($info);
    }
    public function getTypes(Request $request)
    {
        return success(Category::$types);
    }
    // 初始化聊天室
    public function initRoom()
    {
        $success = [];
        $error = [];
        Category::where('room_id','0')->get()->map(function($item) use (&$success, &$error) {
            $room = strtoupper($item->name);
            if (! $room_id = ERoom::create($room, $room, 'admin')) {
                $error[] = $room;
            } else {
                $item->room_id = $room_id;
                if($item->save()){
                    $success[] = [$room => $room_id];
                }else{
                    $error[] = $room;
                }
            }
        });
        dd(compact('success','error'));
    }

}
