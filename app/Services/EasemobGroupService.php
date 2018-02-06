<?php

namespace App\Services;

use App\Model\EasemobGroup;
use App\Model\Category;
use Illuminate\Support\Facades\DB;

class EasemobGroupService extends EasemobApiService
{
    public $uri = 'chatgroups';

    public function create($groupname, $desc, $owner, $maxusers = 2000)
    {
        $param = [
            'groupname' => $groupname,
            'desc' => $desc,
            'public' => false,
            'maxusers' => $maxusers,
            'members_only' => false,
            'allowinvites' => false,
            'owner' => $owner,

        ];
        $res = $this->sendCurl($this->uri, $param, 'POST');

        if(empty($res['data']['groupid'])) {
            return false;
        }
        return $res['data']['groupid'];
    }

    /**
    * @param $category_id 项目ID
    * @param $type 分组类型
    * @param $user 通知用户
    *
    */
    public function addMember($category_id, $type, $user)
    {
        DB::beginTransaction();
        try{
            $group_id = $this->getAvailableEasemobGroupInfo($category_id, $type);
            $uri = $this->uri . '/' . $group_id . '/users/' . $user;
            $res = $this->sendCurl($uri, [], 'POST');
            if(empty($res['data']['result'])){
                return false;
            }
            DB::commit();
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            throw new Exception(trans('custom.FAIL'), API_ADD_GROUP_USER_FAIL);
            DB::rollBack();
            return false;
        }
        return true;
    }

    // 移除组成员
    public function removeMember($category_id, $type, $user)
    {
        // 获取可用组
        $groups = EasemobGroup::where('category_id', $category_id)
                                ->where('type', $type)
                                ->get();
        foreach($groups as $group){
            try {
                $uri = $this->uri . '/' . $group->group_id . '/users/' . $user;
                $res = $this->sendCurl($uri, [], 'DELETE');
                if(!empty($res['data']['result'])){
                    $group->timestamps = false;
                    return $group->decrement('members');
                }
            } catch (\Exception $e) {

            }
        }
        return true;
    }


    // 获取可用项目组
    public function getAvailableEasemobGroupInfo($category_id, $type)
    {
        // 获取可用组
        $groups = EasemobGroup::where('category_id', $category_id)
                                ->where('type', $type)
                                ->where('members','<',2000)
                                ->first();
        if(empty($groups)){
            // 获取组名
            $category = Category::find($category_id,['name']);
            $category_name = $category_id;
            if(!empty($category)){
                // throw new \Exception('没有找到项目');
                $category_name = $category->name;
            }
            $group_name = strtoupper(EasemobGroup::$types[$type] . '-' . $category_name);
            $group_desc = '';
            $owner      = 'admin';
            /**
            * 如果没有可用组就创建一个组
            */
            if(! $group_id = $this->create($group_name, $group_desc, $owner)){
                throw new \Exception('创建 环信 组失败');
            }

            $data = [
                'category_id' => $category_id,
                'type' => $type,
                'group_id' => $group_id
            ];

            if(! $groups = EasemobGroup::create($data)){
                throw new \Exception('保存群组失败');
            }
        }
        $groups->timestamps = false;
        $groups->increment('members');

        return $groups->save() ? $groups->group_id : false;
    }
}
