<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/1/29
 * Time: 10:13
 */

namespace app\platform\service;


use app\platform\model\Admin;
use think\Loader;
use think\Session;

class LoginService
{
    public static function login($validate = 'Login')
    {
        $data = request()->param();
        $validate = Loader::validate($validate);
        if (!$validate->check($data)) {
            return ['code' => 1, 'msg' => $validate->getError()];
        }
        $admin = Admin::get([
            'username' => $data['username'],
            'is_delete' => 0,
        ]);
        if (!$admin) {
            return [
                'code' => 1,
                'msg' => '用户名或密码错误',
            ];
        }

        Session::set('platform_admin_login', 1);

        return [
            'code' => 0,
            'msg' => '登录成功',
        ];
    }

    public static function isLogin()
    {
        $session = Session::get('platform_admin_login');
        $flag = $session ? $session : false;
        return $flag;
    }
}