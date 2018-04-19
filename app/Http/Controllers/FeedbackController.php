<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Feedback;

class FeedbackController extends BaseController
{
    public function index(Request $request)
    {
        $list = Feedback::with('user');

        if($user = $request->get('user')){
            $list->whereHas('user', function($query) use ($user) {
                $user = '%'.$user.'%';
                $query->where('name', 'like', $user);
                $query->orWhere('email', 'like', $user);
            });
        }
        if($type = $request->get('type')){
            $list->where('type', $type);
        }

        if(!is_null($status = $request->get('status'))){
            $list->where('status', $status);
        }
        $list = $list->paginate($this->per_page);

        return success($list);
    }
    public function show(Request $request, $id)
    {
        $info = Feedback::with('user')->find($id);
        $info->status = 1;
        $info->save();
        return success($info);
    }

}
