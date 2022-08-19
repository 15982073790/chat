<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/1/28
 * Time: 18:27
 */
namespace app\platform\model;

use think\Model;

class Admin extends Model
{
    protected $field = true;

    protected $table = 'wolive_admin';

    public function business()
    {
        return $this->hasMany('Business', 'admin_id', 'id');
    }
}