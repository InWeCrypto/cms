<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\CategoryUser;

class CategoryUserController extends BaseController
{
    public function index(Request $request)
    {
        $list = CategoryUser::with(['user','category'])->withCount('comment');

        if ($keyword_for_user = $request->get('keyword_for_user')) {
            $list->whereHas('user', function ($query) use ($keyword_for_user) {
                $keyword = '%'.$keyword_for_user.'%';
                $query->where('name', 'like', $keyword);
                $query->orWhere('name', 'like', $keyword);
            });
        }

        $score_begin = $request->get('score_begin', 0);
        $score_end = $request->get('score_end', 5);

        $list->whereBetween('score', [$score_begin, $score_end]);

        $list = $list->paginate();

        return success($list);
    }


    public function update(Request $request, $c_u_id)
    {
        $info = CategoryUser::find($c_u_id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }

    public function show(Request $request, $c_u_id)
    {
        $info = CategoryUser::find($c_u_id);
        return success($info);
    }
}
