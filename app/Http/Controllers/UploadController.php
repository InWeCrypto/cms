<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OSS;

class UploadController extends BaseController
{
    public function img(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'img' => 'required'
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $file = $request->file('img');
        if(!$file->isValid()){
            return fail('请选择上传的图片');
        }
        $extension = $file->getClientOriginalExtension();
        $file_name = $file->getPathName();
        $oss_file  = 'imgs/' . md5_file($file_name) . '.' . $extension;
        $img       = OSS::updateImg($file_name, $oss_file);
        return success(compact('img'));
    }
}