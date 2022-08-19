<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/1/14
 * Time: 9:57
 */
namespace app\api\controller;

use app\api\validate\StoreMchGet;
use app\common\exception\ApiException;
use think\Db;

class Group extends Base
{
    public function getGroup($business_id = 1)
    {
        (new StoreMchGet())->goCheck();
        $business = Db::name('business')
            ->where('id', $business_id)
            ->find();
        if (!$business) {
            throw new ApiException([
                'msg' => '请求客服系统不存在',
                'errorCode' => 40000
            ]);
        }
        $data = Db::name('group')
            ->field('id,groupname')
            ->where('business_id', $business_id)
            ->select();
        $data = empty($data) ? [['id' => 0, 'groupname' => '通用分组']] : $data;
        return json([
            'code' => 0,
            'data' => $data
        ]);
    }
}