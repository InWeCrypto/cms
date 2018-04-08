<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\CategoryUserComment;

class CategoryUserCommentController extends BaseController
{
    public function index(Request $request, $c_u_id)
    {
        $list = CategoryUserComment::with(['user','category'])->where('category_user_id', $c_u_id);

        if ($keyword_for_user = $request->get('keyword_for_user')) {
            $list->whereHas('user', function ($query) use ($keyword_for_user) {
                $keyword = '%'.$keyword_for_user.'%';
                $query->where('name', 'like', $keyword);
                $query->orWhere('name', 'like', $keyword);
            });
        }

        $list = $list->paginate();

        return success($list);
    }

    public function update(Request $request, $c_u_id)
    {
        $info = CategoryUserComment::find($c_u_id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }

    public function show(Request $request, $c_u_id)
    {
        $info = CategoryUserComment::find($c_u_id);
        return success($info);
    }
}
