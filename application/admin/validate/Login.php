<?php


namespace app\admin\validate;

use think\Validate;

/**
 *
 * 登陆验证器.
 */
class Login extends Validate
{

    /**
     * 验证规则.
     * [$rule description]
     * @var array
     */
    protected $rule = [
        'user_name' => 'require',
        'business_name' => 'require',
        'password' => 'require',
        'captcha' => 'require|captcha:admin_login',
        'repassword' => 'require|confirm:password'
    ];

    /**
     * 验证消息.
     * [$messege description]
     * @var [type]
     */
    protected $messege = [
        'business_name.require' => '请填写商家名称',
        'username.require' => '请填写帐号',
        'password.require' => '请填写密码',
        'captcha.require' => '请填写验证码',
        'repassword.require' => '确认密码不能为空',
        'repassword.confirm' => '密码和确认密码不相等',
        'captcha.captcha' => '验证码不正确'
    ];
    protected $scene = [
        'login' => ['user_name', 'password', 'captcha'],
        'regist' => ['user_name', 'password', 'captcha', 'repassword', 'business_name']
    ];
}