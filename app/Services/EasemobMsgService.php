<?php

namespace App\Services;

class EasemobMsgService extends EasemobApiService
{
    public $uri = 'messages';

    public $target_type = null;
    public $target      = [];
    public $msg         = [];
    public $from        = null;
    public $ext         = [];

    public function __call($func, $args)
    {
        // 设置消息对象
        if( $this->target_type = \EasemobMsg::$target_types[$func] ?? null ){
            $users = [];
            array_walk_recursive($args, function($value) use (&$users) {
                array_push($users, $value);
            });
            $this->target = $users;
            // dd($users);
            unset($users);
        }
        return $this;
    }

    public function from($user)
    {
        $this->from = $user;
        return $this;
    }

    public function txt($msg)
    {
        $this->msg = [
             "type" => "txt",
             "msg" => $msg
        ];
        return $this;
    }

    // 通知类型
    public function case($type)
    {
        $this->ext = array_merge($this->ext, compact('type'));

        return $this;
    }

    public function send()
    {
        $param = [
            'target_type' => $this->target_type,
            'target' => $this->target,
            'msg' => $this->msg,
            'from' => $this->from
        ];
        if ($ext = $this->ext) {
            $param = array_merge($param, compact('ext'));
        }

        $res = $this->sendCurl($this->uri, $param, 'POST');

        if(empty($res['data'])) {
            return false;
        }
        return true;
    }
}
