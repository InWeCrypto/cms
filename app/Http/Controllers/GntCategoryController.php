<?php

namespace App\Http\Controllers;

use App\Model\GntCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class GntCategoryController
 * @package App\Http\Controllers\Back
 */
class GntCategoryController extends BaseController
{

	/**
	 * @param Request $request
	 * @return array
	 */
	public function index(Request $request)
	{
        $list = GntCategory::ofCategoryId($request->get('category_id', 0))->with('walletCategory');

        if($name = $request->get('name')){
            $list = $list->where('name', 'like', '%' . strtoupper($name) . '%');
        }

        $list = $list->paginate($this->per_page);
		return success($list);
	}


	/**
	 * @param Request $request
	 * @return array
	 */
	public function store(Request $request)
	{
		$validator = \Validator::make($request->all(), [
			'category_id' => 'required',
			'name' => 'required|unique:gnt_categorys,name,null,id,category_id,' . $request->get('category_id'),
		]);

        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

		return GntCategory::create($request->all()) ? success() : fail();
	}


	/**
	 * @param Request $request
	 * @param GntCategory $gntCategory
	 * @return array
	 */
	public function update(Request $request, GntCategory $gntCategory)
	{
		$validator = \Validator::make($request->validator, [
			'name' => 'required|unique:gnt_categorys,name,' . $gntCategory->id . ',id,category_id,' . $gntCategory->category_id,
		]);

        if($validator->fails()){
            return fail($validator->errors()->first(), NOT_VALIDATED);
        }

		DB::transaction(function () use ($gntCategory, $request) {
			$name = $request->get('name');
			$gntCategory->fill($request->all())->save();
			$gntCategory->userGnt()->update(['name' => $name]);
		});

		return success();

	}


	/**
	 * @param GntCategory $gntCategory
	 * @return array
	 */
	public function destroy(GntCategory $gntCategory)
	{
		if ($gntCategory->userGnt()->count()) {
			return fail('', '该代币类型已有用户创建实例,禁止删除');
		}
		return $gntCategory->delete() ? success() : fail();
	}
}
