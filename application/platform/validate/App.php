<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/1/30
 * Time: 17:12
 */

namespace app\platform\validate;

use think\Validate;

class App extends Validate
{

    /**
     * 验证规则.
     * [$rule description]
     * @var array
     */
    protected $rule = [
        'business_name' => 'require|length:3,16|chsDash',
        'user_name' => 'require|length:3,16|alphaDash',
        'password' => 'require|length:6,16',
        'max_count' => 'require|number',
        'expire_time' => 'date'
    ];

    /**
     * 验证消息.
     * [$messege description]
     * @var [type]
     */
    protected $message = [
        'business_name.require' => '请填写客服系统名称',
        'business_name.length' => '客服系统名称为3~16个字符',
        'business_name.chsDash' => '客服系统名称只能是汉字、字母、数字和下划线_及破折号-',
        'user_name.require' => '请填写帐号',
        'user_name.length' => '管理员账号为3~16个字符',
        'user_name.alphaDash' => '管理员账号只能是字母、数字、下划线 _ ',
        'password.require' => '请填写登录密码',
        'password.length' => '密码长度为1~16个字符',
        'max_count.require' => '请填写数量',
        'max_count.number' => '客服数量只能是数字',
        'expire_time' => '有效期格式不正确',
    ];

    protected $scene = [
        'edit' => ['business_name', 'max_count'],
        'insert' => ['business_name', 'user_name', 'password', 'max_count']
    ];
}