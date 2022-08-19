<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/2/18
 * Time: 16:20
 */
namespace app\admin\model;

use app\platform\model\Business;
use think\Model;

class TplService extends Model
{
    protected $field = true;

//    protected $table = 'wolive_wechat_platform';

    /**
     * 发送模板消息
     */
    public static function send($business_id, $openid, $url, $tpl, $data)
    {
//        $notice = WechatService::get($business_id)->notice;
        $business = Business::get($business_id);
//        $business['template_state'] == 'open' && $notice->to($openid)->uses($tpl)->andUrl($url)->data($data)->send();
        if ($business['template_state'] == 'open') {
            $res = WechatService::get($business_id)->template_message->send([
                'touser' => $openid,
                'template_id' => $tpl,
                'url' => $url,
                'data' => $data
            ]);
            $time = date('Y-m-d H:i:s');
            file_put_contents(PUBLIC_PATH . '/sendtpl.txt', "[{$time}]" . serialize($res) . "\r\n");
            return $res;
        }
        return true;
    }

    /**
     * 发送一次性订阅消息
     */
    public static function onesubsend($business_id, $openid, $url, $tpl, $scene, $title, $data)
    {
//        $notice = WechatService::get($business_id)->notice;
        $business = Business::get($business_id);
//        $business['template_state'] == 'open' && $notice->to($openid)->uses($tpl)->andUrl($url)->data($data)->send();
        if ($business['template_state'] == 'open') {
//            $app->template_message->sendSubscription([
//        'touser' => 'user-openid',
//        'template_id' => 'template-id',
//        'url' => 'https://easywechat.org',
//        'scene' => 1000,
//        'data' => [
//            'key1' => 'VALUE',
//            'key2' => 'VALUE2',
//            ...
//        ],
//    ]);
            $res = WechatService::get($business_id)->template_message->sendSubscription([
                'touser' => $openid,
                'template_id' => $tpl,
                'url' => $url,
                'scene' => $scene,
                'title' => $title,
                'data' => $data
            ]);
            $time = date('Y-m-d H:i:s');
            file_put_contents(PUBLIC_PATH . '/onesubsend.txt', "[{$time}]" . serialize($res) . '[openid]=' . $openid . "\r\n", FILE_APPEND);
            return $res;
        }
        return true;
    }
}