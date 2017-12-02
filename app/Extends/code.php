<?php
/**
 * Created by PhpStorm.
 * User: zhaoyadong
 * Date: 2017/5/18
 * Time: 下午4:36
 *
 * 结果码对照表
 */

/**
 *  4000    请求执行成功
 */
define('SUCCESS', 4000);
/**
 *4001    未登陆
 */
define('NOT_LOGIN', 4001);
/**
 *4002    无权限
 */
define('NOT_PERMISSION', 4002);
/**
 *4003    路由不存在
 */
define('NOT_FIND_METHOD', 4003);
/**
 *4004    验证不通过
 */
define('NOT_VALIDATED', 4004);
/**
 *4005    查询数据不存在
 */
define('NOT_FIND_RECORD', 4005);
/**
 *4006    请求执行失败
 */
define('FAIL', 4006);
/**
 *4007    请求执行成功,即将跳转
 */
define('WILL_REDIRECT', 4007);
/**
 *4008    未注册
 */
define('NOT_REGISTER', 4008);
/**
 * 4009 token 无效
 */
define('INVALID_TOKEN', 4009);
/*
 *4010    token过期
 */
define('EXPIRED_TOKEN', 4010);

/**
 * 5000 发送验证码成功
 */
define('SEND_VERIFY_SUCCESS', 5000);
/**
 * 5001 发送验证码失败，重试！
 */
define('SEND_VERIFY_ERROR', 5001);
/**
 * 5002 发送验证码失败, N 秒后重试！(重复获取验证码)
 */
define('VERIFY_REPEAT', 5002);
/**
 * 5003 验证码已失效！
 */
define('VERIFY_INVALID', 5003);
/**
 * 5004 验证码错误！
 */
define('VERIFY_ERROR', 5004);
/**
 * 5005 与上次手机号不一致，请在 {$diff} 秒后重试！
 */
define('VERIFY_USER_DIFF', 5005);
/**
 * 5006 获取验证码失败, 不是授权用户！
 */
define('VERIFY_USER_NOT_PERMISSION', 5006);
/**
 * 5007 发送邮箱失败
 */
define('SEND_MAIL_FAIL', 5007);
/**
 * 5008 发送邮箱成功
 */
define('SEND_MAIL_SUCCESS', 5008);
/**
 * 5101 注册用户的时候写入User表失败
 */
define('WRITE_USER_FAIL', 5101);
/**
 * 5201 创建用户token失败, 即登录失败
 */
define('CREATE_USER_TOKEN_FAIL', 5201);



/**
 * 用户评论错误
 */
// 自己评论自己
define('COMMENT_OWN', '5301');

/**
 * 用户消息
 */
// 写入消息表错误
define('MESSAGE_CREATE_ERROR', '5401');


/**
 * 请求外部接口
 */
// 获取数据为空
define('API_GET_DATA_FAIL', '5501');

