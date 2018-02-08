<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\FacArticleCommentes\DB;
use App\Model\ArticleComment;

class ArticleCommentController extends BaseController
{
    public function index(Request $request)
    {
        $list = ArticleComment::with(['article', 'user'])->paginate($this->per_page);

        return success($list);
    }
    public function update(Request $request, $id)
    {
        $info = ArticleComment::find($id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $id)
    {
        $info = ArticleComment::find($id);
        $info->enable = false;
        return $info->save() ? success() : fail();
    }

}
