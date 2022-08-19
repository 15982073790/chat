<?php

namespace app\weixin\controller;

use app\admin\model\WechatPlatform;
use app\platform\enum\apps;
use app\platform\model\Business;
use app\platform\model\Service;
use think\Controller;
use app\weixin\model\Admins;
use EasyWeChat\Foundation\Application;
use think\Cookie;


class Login extends Controller
{
    private $business_id = null;

    public function index()
    {
        session('Msg', null);
        Cookie::delete('service_token');
        $code = $this->request->param('code');//获取code
        if ($code) {

            $this->business_id = $this->request->param('business_id');
            $wechat = WechatPlatform::get(['business_id' => $this->business_id]);
            $appid = $wechat['app_id'];
            $appsecret = $wechat['app_secret'];
            $weixin = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code");//通过code换取网页授权access_token
            $jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
            $array = get_object_vars($jsondecode);//转换成数组
            $openid = $array['openid'];//输出openid
            $service = Service::get(['open_id' => $openid, 'business_id' => $this->business_id]);
            empty($service) && $this->redirect('admin/login/index', ['business_id' => $this->business_id]);
            session('Msg', $service->getData());
            $business = Business::get(session('Msg.business_id'));

            session('Msg.business', $business->getData());
            session('Msg.openid', $openid);
            $this->redirect('mobile/admin/index');
        } else {
            $this->redirect('admin/login/index');
        }
    }

    public function callback()
    {
        $this->business_id = $this->request->param('business_id', Cookie::get('YMWL_APP_FLAG'));
        empty($this->business_id) ? abort(500) : Cookie::set('YMWL_APP_FLAG', $this->business_id);
        $wechat = WechatPlatform::get(['business_id' => $this->business_id]);
        $APPID = $wechat['app_id'];
        $REDIRECT_URI = url('weixin/login/index', ['business_id' => $this->business_id], true, true);
        $scope = 'snsapi_base';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $APPID . '&redirect_uri=' . urlencode($REDIRECT_URI) . '&response_type=code&scope=' . $scope . '&state=123#wechat_redirect';

        $this->redirect($url);
    }
}