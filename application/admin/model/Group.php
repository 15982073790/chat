<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/4/10
 * Time: 11:21
 */
namespace app\admin\model;

use think\Model;

class Group extends Model
{
    protected $table = 'wolive_group';
    protected $autoWriteTimestamp = false;

    public function setCreateTimeAttr()
    {

    }

    public function getCreateTimeAttr()
    {

    }
}