<?php
/**
 * @copyright ©2020 来客PHP在线客服系统
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/5/9
 * Time: 10:04
 */

namespace app\admin\model;

use think\Model;

class Comment extends Model
{
    protected $table = 'wolive_comment';

    public function detail()
    {
        return $this->hasMany('CommentDetail', 'comment_id', 'id');
    }

    public function service()
    {
        return $this->hasOne('app\platform\model\Service', 'service_id', 'service_id');
    }

    public function group()
    {
        return $this->hasOne('Group', 'id', 'group_id');
    }
}