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

    public function policy($dir)
    {
        $maxSize    = 100; // 最大上传100M
        $expireTime = 900;
        $accessid   = config('oss.AccessKeyId');
        $appsecret  = config('oss.AccessKeySecret');
        $host       = config('oss.seePrefix');
        $bucket     = config('oss.bucket');
        $end        = time() + $expireTime;
        $expiration = $this->gmt_iso8601($end);

        $conditions = [];
        $conditions[] = array(0=>'content-length-range', 1=>0, 2=>1024*1024*$maxSize); // 最大文件大小.用户可以自己设置 100M

        $start = array(0=>'starts-with', 1=>'$key', 2=>$dir); //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $conditions[] = $start;

        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $appsecret, true));

        $response = array();
        $response['accessid'] = $accessid;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['bucket'] = $bucket;
        $response['dir'] = $dir;  //这个参数是设置用户上传指定的前缀

        return $response;
    }

    public function gmt_iso8601($time)
    {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration . "Z";
    }
}