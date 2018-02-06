<?php

return [
    'useInternal' => false,//是否使用OSS内网传输来省流量
    'server' => 'http://oss-cn-shanghai.aliyuncs.com',
    'serverInternal' => 'http://oss-cn-shanghai-internal.aliyuncs.com',
    'AccessKeyId' => env('ALI_KEY'),
    'AccessKeySecret' => env('ALI_SECRETKEY'),
    'seePrefix' => 'http://inwecrypto-china.oss-cn-shanghai.aliyuncs.com',
    'bucket' => 'inwecrypto-china',
];
