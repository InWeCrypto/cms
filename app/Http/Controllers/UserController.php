<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;

class UserController extends BaseController
{
    public function index(Request $request)
    {
        $info = User::paginate(5);
        return success($info);
    }

    public function show(Request $request, $id)
    {
        return success(User::find($id));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'name' => 'required|unique:users,name',
                'phone' => 'unique:users,phone',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $info = new User();
        $info->fill($request->all());
        $info->password = bcrypt($info->password);
        return $info->save() ? success() : fail();
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
                'name' => 'required|unique:users,name,' . $id,
                'email' => 'required|email|unique:users,email,' .$id,
                'phone' => 'unique:users,phone,' .$id,
                'password' => 'confirmed|min:6',
            ]
        );

        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }

        $info = User::find($id);
        $info->fill($request->all());
        if(isset($info->password)){
            $info->password = bcrypt($info->password);
        }
        return $info->save() ? success() : fail();
    }

    public function destroy(Request $request, $id){
        return User::find($id)->delete() ? success() : fail();
    }

}