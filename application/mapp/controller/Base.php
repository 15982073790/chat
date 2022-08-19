<?php


namespace app\mapp\controller;

use think\Controller;
use think\Loader;
use think\Response;
use think\Session;
use app\admin\model\Admins;

/**
 * 基础验证是否登录.
 */
class Base extends Controller
{
    protected $base_root = null;

    /**
     * 验证session.
     *
     * @return void
     */
    public function _initialize()
    {
        if (!session('Msg')) {
            $this->redirect('admin/login/index');
        }
        $login = session('Msg');
        $res = model('business')->where('id', $login['business_id'])->find();
        $group = model('group')->where('business_id', $login['business_id'])->select();
        $groupjson = json_encode($group);
        $app_key = app_key;

        $arr = parse_url(whost);

        if ($arr['scheme'] == 'ws') {
            $value = 'false';
            $port = 'wsPort';
        } else {
            $value = 'true';
            $port = 'wssPort';
        }
        $this->base_root = BASEROOT;
        $this->assign('baseroot', $this->base_root);
        $this->assign('seo', session('Msg.business'));
        $this->assign('app_key', $app_key);
        $this->assign('whost', $arr['host']);
        $this->assign('value', $value);
        $this->assign('wport', wport);
        $this->assign('user', $login);
        $this->assign('port', $port);
        $this->assign('group', $groupjson);
        $this->assign('voice', $res['voice_state']);
        $this->assign('voice_address', $res['voice_address']);

    }

}