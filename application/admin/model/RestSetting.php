<?php
/**
 * @copyright ©2020 来客PHP在线客服系统
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/5/14
 * Time: 15:49
 */

namespace app\admin\model;

use think\Model;

class RestSetting extends Model
{
    protected $table = 'wolive_rest_setting';

    public function isOpen($business_id, $visiter_id)
    {
        if ($this->state == 'close') {
            return false;
        }

        if (!$this->checkIsWriteInfo($business_id, $visiter_id)) {
            return false;
        }

        if ($this->checkIsBetweenwWeek()) {
            if ($this->checkIsBetweenTime()) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    private function checkIsBetweenwWeek()
    {
        $date = date('N');
        if (in_array($date, json_decode($this->week, true))) {
            return true;
        }
        return false;
    }

    private function checkIsBetweenTime()
    {
        $date = date('H:i');
        $curTime = strtotime($date);//当前时分
        $assignTime1 = strtotime($this->start_time);//获得指定分钟时间戳，00:00
        $assignTime2 = strtotime($this->end_time);//获得指定分钟时间戳，01:00
        if ($curTime > $assignTime1 && $curTime < $assignTime2) {
            return true;
        }
        return false;
    }

    private function checkIsWriteInfo($business_id, $visiter_id)
    {
        $visiter = Visiter::get(['business_id' => $business_id, 'visiter_id' => $visiter_id]);
        if (empty($visiter['name']) && empty($visiter['tel'])) {
            return true;
        }
        return false;
    }
}