<?php

namespace app\weixin\controller;

use think\Controller;

class Base extends Controller
{
    public function _initialize()
    {
        if (!session('Msg')) {
            $APPID = appid;

            $url = domain;
            $REDIRECT_URI = $url . '/weixin/login';
            $scope = 'snsapi_base';
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $APPID . '&redirect_uri=' . urlencode($REDIRECT_URI) . '&response_type=code&scope=' . $scope . '&state=123#wechat_redirect';

            $this->redirect($url);
        }

        $login = session('Msg');
        $app_key = app_key;
        $whost = whost;
        $arr = parse_url($whost);
        if ($arr['scheme'] == 'ws') {
            $value = 'false';
            $port = 'wsPort';
        } else {
            $value = 'true';
            $port = 'wssPort';
        }

        $this->assign('app_key', $app_key);
        $this->assign('whost', $arr['host']);
        $this->assign('value', $value);
        $this->assign('wport', wport);
        $this->assign('port', $port);
        $this->assign('arr', $login);
    }
}