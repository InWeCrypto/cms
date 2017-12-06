<?php

return [
    'useInternal' => false,//是否使用OSS内网传输来省流量
    'server' => 'http://oss-cn-hongkong.aliyuncs.com',
    'serverInternal' => 'http://oss-cn-hongkong-internal.aliyuncs.com',
    'AccessKeyId' => env('ALISMS_KEY'),
    'AccessKeySecret' => env('ALISMS_SECRETKEY'),
    'seePrefix' => 'http://whalewallet.oss-cn-hongkong.aliyuncs.com',
    'bucket' => 'whalewallet',
];