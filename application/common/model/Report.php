<?php
/**
 * @copyright ©2021 来客PHP在线客服系统
 */

namespace app\common\model;

use think\Model;

class Report extends Model
{

    public function business()
    {
        return $this->hasOne('Business', 'id', 'business_id');
    }
}