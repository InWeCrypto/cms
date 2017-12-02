<?php

namespace App\Services;
 
use OSS\OssClient;
 
class OssService {
    
    public function updateImg($local_file, $oss_file){
        $bucket = config('oss.bucket');
        $ak     = config('oss.AccessKeyId');
        $sk     = config('oss.AccessKeySecret');
        $host   = config('oss.useInternal', true) ? config('oss.serverInternal') : config('oss.server');
        $ossClient = new OssClient($ak, $sk, $host, false);

        if (is_null($ossClient)) {
            return false;
        }

        $res = $ossClient->uploadFile($bucket, $oss_file, $local_file);

        return $res['oss-request-url'] ?: false;
    }

    public function updateFile($local_file, $oss_file){
        $bucket = config('oss.bucket');
        $ak     = config('oss.AccessKeyId');
        $sk     = config('oss.AccessKeySecret');
        $host   = config('oss.useInternal', true) ? config('oss.serverInternal') : config('oss.server');
        $ossClient = new OssClient($ak, $sk, $host, false);

        if (is_null($ossClient)) {
            return false;
        }

        $res = $ossClient->uploadFile($bucket, $oss_file, $local_file);

        return $res['oss-request-url'] ?: false;
    }

    public function list($prefix, $nextMarker='', $delimiter='/', $maxkeys=30)
    {
        $bucket = config('oss.bucket');
        $ak     = config('oss.AccessKeyId');
        $sk     = config('oss.AccessKeySecret');
        $host   = config('oss.useInternal', true) ? config('oss.serverInternal') : config('oss.server');
        $see_prefix = config('oss.seePrefix');
        $ossClient = new OssClient($ak, $sk, $host, false);

        if (is_null($ossClient)) {
            return false;
        }

        $option = [
            'delimiter' => $delimiter,
            'prefix' => $prefix,
            'max-keys' => $maxkeys,
            'marker' => $nextMarker,
        ];
        $return = [];
        foreach($ossClient->listObjects($bucket, $option)->getObjectList() as $obj){
            $return[] = [
                'url' => $see_prefix.'/'.$obj->getKey(),
                'mtime' => strtotime($obj->getLastModified())
            ];
        }
        return $return;
    }
}