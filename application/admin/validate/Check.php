<?php


namespace app\admin\validate;

use think\Validate;

/**
 *
 * 登陆验证器.
 */
class Check extends Validate
{

    /**
     * 验证规则.
     * [$rule description]
     * @var array
     */
    protected $rule = [
        'oldpass' => 'require',
        'newpass' => 'require|length:6,16"',
        'newpass2' => 'require|confirm:newpass'
    ];

    /**
     * 验证消息.
     * [$messege description]
     * @var [type]
     */
    protected $message = [
        'oldpass.require' => '请填写旧密码',
        'newpass.require' => '请填写新密码',
        "newpass.length" => "密码长度为6~16个字符",
        'newpass2.require' => '请再次填写新密码',
        "newpass2.confirm" => "新密码不一致",
    ];

    protected $scene = [
        'change_service_pwd' => ['newpass'],
    ];
}
