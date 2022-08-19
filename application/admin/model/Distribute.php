<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/2/18
 * Time: 18:35
 */
namespace app\admin\model;

class Distribute
{
    public static function run($business_id, $state = null, $groupid)
    {
        $where = [
            'business_id' => $business_id,
            'state' => $state,
            'groupid' => $groupid,
        ];

        $service_data = [];

        if ($state == null) {
            unset($where['state']);
            $where['offline_first'] = 1;
            $services = self::getList($where);
            if ($services) {
                self::sort($services, $service_data);
            } else {
                $where['groupid'] = 0;
                $services_all = self::getList($where);
                if ($services_all) {
                    self::sort($services_all, $service_data);
                }
            }
            reset($service_data);
            if (!empty($service_data)) {
                return $service_data;
            } else {
                unset($where['offline_first']);
            }
        }

        $services = self::getList($where);
        if ($services) {
            self::sort($services, $service_data);
        } else {
            $where['groupid'] = 0;
            $services_all = self::getList($where);
            if ($services_all) {
                self::sort($services_all, $service_data);
            }
        }
        reset($service_data);
        return $service_data;
    }

    public static function sort($services, &$service_data)
    {
        foreach ($services as $v) {
            $service_id = $v['service_id'];
            $num = model('queue')->where(['service_id' => $v['service_id']])->count();

            if (isset($service_data['num'])) {

                if ($service_data['num'] > $num) {
                    $v['num'] = $num;
                    $service_data = $v;
                }
            } else {
                $v['num'] = $num;
                $service_data = $v;
            }
        }
    }

    public static function getList($where)
    {
        return model('service')
            ->field('avatar,business_id,email,groupid,open_id,nick_name,service_id,state')
            ->where($where)
            ->select();
    }
}