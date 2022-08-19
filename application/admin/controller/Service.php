<?php


namespace app\admin\controller;

use app\common\lib\SinglePusher;
use app\platform\model\Business;
use app\platform\model\Option;
use think\Controller;
use think\captcha\Captcha;
use think\config;
use think\Db;
use app\Common;
use think\Cookie;


/**
 * 登录控制器.
 */
class Service extends Base
{

    protected $login;

    public function _initialize()
    {
        parent::_initialize();
        $this->login = session('Msg');
    }

    /**
     * 登陆日志
     *
     * @return string
     */
    public function loginlog()
    {
        $list = Db::name('login_log')
            ->where(['uid' => $this->login['service_id'], 'source' => 2])
            ->paginate();
        $this->assign('list', $list);
        $this->assign('title', "客服登录日志列表");
        $this->assign('part', "登录日志");
        return $this->fetch();
    }

}