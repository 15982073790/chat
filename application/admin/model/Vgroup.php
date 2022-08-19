<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/4/10
 * Time: 11:21
 */
namespace app\admin\model;

use think\Model;

class Vgroup extends Model
{
    protected $autoWriteTimestamp = false;

    public function setCreateTimeAttr()
    {

    }

    public function getCreateTimeAttr($value, $data)
    {
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }
}