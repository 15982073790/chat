<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/1/30
 * Time: 13:55
 */
namespace app\platform\behavior;

use app\platform\service\Auth;
use app\platform\service\Permissions;

class Permission
{
    private $auth = null;

    public function run(&$params)
    {
        $this->auth = Auth::instance();
        $modulename = strtolower(request()->module());
        $controllername = strtolower(request()->controller());
        $actionname = strtolower(request()->action());
        $token = request()->server('HTTP_TOKEN', request()->request('token', \think\Cookie::get('token')));
        $path = str_replace('.', '/', $controllername) . '/' . $actionname;
        // 设置当前请求的URI
        $this->auth->setRequestUri($path);
        //初始化
        $this->auth->init($token);

        $userInfo = $this->auth->getUserinfo();
        if ($userInfo['id'] == 1) {
            return true;
        }

        $route = $modulename . '/' . $controllername . '/' . $actionname;
        $permissions = Permissions::getCAdminPermission();
        if (in_array($route, $permissions)) {
            return true;
        }

        if (request()->isAjax) {
            return [
                'code' => 1,
                'msg' => '您不是超级管理员，无操作权限',
            ];
        } else {
            $url = url('platform/user/me', '', true, true);
            header("location:" . $url);
            exit;
        }
    }
}