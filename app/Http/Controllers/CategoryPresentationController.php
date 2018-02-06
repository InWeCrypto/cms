<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Article;

class CategoryPresentationController extends BaseController
{
    public function index(Request $request, $cat_id)
    {
        $list = Article::where('category_id', $cat_id);

        if($lang = $request->get('lang')){
            $list = $list->where('lang', $lang);
        }

        $list = $list->where('type', Article::DESC)
                    ->get()
                    ->makeHidden([
                        'category_id',
                        'type',
                        'author',
                        'img',
                        'url',
                        'video',
                        'sort',
                        'click_rate',
                        'is_hot',
                        'is_top',
                        'is_scroll',
                        'is_sole',
                        'enable',
                    ]);

        return success($list);
    }
    public function store(Request $request, $cat_id)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'lang' => 'required',
        ]);
        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

        $info = new Article();

        $info->fill($request->all());
        $info->category_id = $cat_id;
        $info->type = Article::DESC;

        return $info->save() ? success() : fail();
    }
    public function update(Request $request, $cat_id, $art_id)
    {
        $info = Article::where('category_id', $cat_id)->find($art_id);
        $info->fill($request->all());

        return $info->save() ? success() : fail();
    }
    public function destroy(Request $request, $cat_id, $art_id)
    {
        $info = Article::where('category_id', $cat_id)->find($art_id);

        return $info->delete() ? success() : fail();
    }
    public function show(Request $request, $cat_id, $art_id)
    {
        $info = Article::where('category_id', $cat_id)
                            ->find($art_id)
                            ->makeHidden([
                                'category_id',
                                'type',
                                'author',
                                'img',
                                'url',
                                'video',
                                'sort',
                                'click_rate',
                                'is_hot',
                                'is_top',
                                'is_scroll',
                                'is_sole',
                                'enable',
                            ])
                            ->makeVisible([
                                'content'
                            ]);
        return success($info);
    }

}
