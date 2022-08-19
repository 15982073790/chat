<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/2/18
 * Time: 10:35
 */
namespace app\admin\model;
use EasyWeChat\Factory;

class WechatService
{

    public static function get($bid = null)
    {
        $business_id = !empty($bid) ? $bid : session('Msg.business_id');
        $wechat = WechatPlatform::get(['business_id' => $business_id]);
        $option = [
            'app_id' => $wechat['app_id'],
            'secret' => $wechat['app_secret'],
            'aes_key' => $wechat['wx_aeskey'],
            'token' => $wechat['wx_token'],
        ];
//        return new Application($option);
        return Factory::officialAccount($option);
    }

    public static function getUserinfo($openId)
    {
        $a = self::get()->user;
        $user = $a->get($openId);
        return $user;
    }

}