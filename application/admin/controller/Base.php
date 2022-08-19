<?php


namespace app\admin\controller;

use app\Common;
use app\platform\model\Admin;
use app\platform\model\Business;
use app\weixin\model\Weixin;
use think\Controller;
use think\Cookie;
use think\Loader;
use think\Response;
use think\Session;
use app\admin\model\Admins;
use think\Db;

/**
 * 基础验证是否登录.
 */
class Base extends Controller
{

    protected $base_root = null;
    public $wechat_platform = null;
    public $open_id = '';

    /**
     * 验证session.
     *
     * @return void
     */
    public function _initialize()
    {
        parent::_initialize();

        if (!session('Msg')) {
            $token = Cookie::get('service_token');
            if (!$token) {
                $this->redirect('admin/login/index');
            }
            $common = new Common();
            $user_name = $common->cpDecode($token, YMWL_SALT);
            if (!$user_name) {
                $this->redirect('admin/login/index');
            }
            $data = model("service")
                ->where('user_name', $user_name)
                ->find();
            if ($data) {
                session('Msg', $data->getData());
                $business = Business::get(session('Msg.business_id'));
                session('Msg.business', $business->getData());
                $this->open_id = session('Msg.open_id');
            }
        } else {
            $serviceInfo = Db::name('service')->field('open_id')->where(['service_id' => session('Msg.service_id')])->find();
            if ($serviceInfo) {
                $this->open_id = $serviceInfo['open_id'];
            }

        }
        if (!session('Msg')) {
            $this->redirect('admin/login/index');
        }

        $login = session('Msg');
        $res = model('business')->where('id', $login['business_id'])->find();
        if ($res['is_recycle'] || $res['is_delete']) {
            session('Msg', null);
            $this->error('系统已被回收或封禁');
        }
        if ($res['expire_time'] < time() && $res['expire_time'] != 0) {
            session('Msg', null);
            $this->error('系统已过期');
        }

        $group = model('group')->where('business_id', $login['business_id'])->select();

        $groupjson = json_encode($group);
        $temp = $login;
        $temp['open_id'] = $this->open_id;
        unset($temp['copyright']);
        unset($temp['business']['copyright']);
        unset($temp['password']);
        $data = json_encode($temp);
        $app_key = app_key;

        $arr = parse_url(whost);

        if ($arr['scheme'] == 'ws') {
            $value = 'false';
            $port = 'wsPort';
        } else {
            $value = 'true';
            $port = 'wssPort';
        }
        $this->assign('baseroot', BASEROOT);
//        $service = Weixin::get(['service_id'=>session('Msg.service_id')]);
        $this->assign('referer', session('Platform.referer'));

        $this->wechat_platform = Db::name('wechat_platform')->where(['business_id' => $login['business_id']])->find();
        $is_bind_wechat = 0;
        if (!$this->open_id && $this->wechat_platform && $this->wechat_platform['app_id'] && $this->wechat_platform['app_secret'] && $this->wechat_platform['visitor_tpl']) {
            $is_bind_wechat = 1;
        }
        $this->assign('is_bind_wechat', $is_bind_wechat);

        $this->assign('seo', session('Msg.business'));
        $this->assign('app_key', $app_key);
        $this->assign('whost', $arr['host']);
        $this->assign('value', $value);
        $this->assign('wport', wport);
        $this->assign('arr', $login);
        $this->assign('data', $data);
        $this->assign('port', $port);
        $this->assign('group', $groupjson);
        $this->assign('voice', $res['voice_state']);
        $this->assign('voice_address', $res['voice_address']);

    }

}