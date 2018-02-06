<?php

namespace App\Services;

use App\Model\EasemobGroup;
use App\Model\Category;
use Illuminate\Support\Facades\DB;

class EasemobRoomService extends EasemobApiService
{
    public $uri = 'chatrooms';

    public function create($name, $desc, $owner, $maxusers = 5000)
    {
        $param = [
            'name' => $name,
            'description' => $desc,
            'maxusers' => $maxusers,
            'owner' => $owner,
        ];
        $res = $this->sendCurl($this->uri, $param, 'POST');

        if(empty($res['data']['id'])) {
            return false;
        }
        return $res['data']['id'];
    }

    public function remove($room_id)
    {
        $uri = $this->uri . '/' . $room_id;

        $res = $this->sendCurl($uri, [], 'DELETE');

        if(empty($res['data']['success'])) {
            return false;
        }
        return true;
    }
}
