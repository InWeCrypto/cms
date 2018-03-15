<?php

namespace App\Services;

class EasemobUserService extends EasemobApiService
{
    public $uri = 'users';

    public function add(array $users)
    {
        $res = $this->sendCurl($this->uri, $users, 'POST');

        if(empty($res['entities'][0]['uuid'])) {
            return false;
        }
        return true;
    }

    public function resetPassword($user, $new_password)
    {
        try {
            $param = [
                "newpassword" => $new_password
            ];
            $uri = $this->uri . '/' . $user .'/password';
            $res = $this->sendCurl($uri, $param, 'PUT');
        } catch () {
            return false;
        }
        return true;
    }
}
