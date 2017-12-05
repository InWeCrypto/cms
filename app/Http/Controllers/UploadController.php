<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OSS;

class UploadController extends BaseController
{
    public function img(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'img' => 'required|mimes:jpeg,bmp,png'
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
        $img       = OSS::updateFile($file_name, $oss_file);
        return success(compact('img'));
    }

    public function video(Request $request)
    {
        $validator = \Validator::make($request->all(), [
                'video' => 'required|mimes:mp4,3gp,flv'
            ]
        );
        if ($validator->fails()){
            return fail($validator->messages()->first(), NOT_VALIDATED);
        }
        $file = $request->file('video');
        if(!$file->isValid()){
            return fail('请选择上传的图片');
        }
        $extension = $file->getClientOriginalExtension();
        $file_name = $file->getPathName();
        $oss_file  = 'videos/' . md5_file($file_name) . '.' . $extension;
        $video     = OSS::updateFile($file_name, $oss_file);
        return success(compact('video'));
    }
}