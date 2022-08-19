<?php

namespace app\weixin\controller;
use app\admin\model\WechatPlatform;
use app\common\lib\SinglePusher;
use think\Controller;
use EasyWeChat\Factory;
use think\Log;
use think\Db;
class  Index extends Controller
{
    public function index()
    {
//       file_put_contents(PUBLIC_PATH.'/wxindex.txt',var_export($_REQUEST,true),FILE_APPEND);
        $business_id = $this->request->param('business_id', null);
        !$business_id && abort(500, '参数错误');
        $wechat = WechatPlatform::get(['business_id' => $business_id]);
        // config配置
        $options = [
//           'debug'  => true,
            'app_id' => $wechat['app_id'],
            'secret' => $wechat['app_secret'],
            'aes_key' => $wechat['wx_aeskey'],
            'token' => $wechat['wx_token'],
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'object',
            /*            'log' => [
                            'file'  => PUBLIC_PATH.'/easywechat.log', // XXX: 绝对路径！！！！
                        ],*/
            /**
             * 日志配置
             *
             * level: 日志级别, 可选为：
             *         debug/info/notice/warning/error/critical/alert/emergency
             * path：日志文件位置(绝对路径!!!)，要求可写权限
             */
            'log' => [
                'default' => 'prod', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => RUNTIME_PATH . 'log/easywechat.log',
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => RUNTIME_PATH . 'log/easywechat.log',
                        'level' => 'info',
                    ],
                ],
            ],
        ];
        $app = Factory::officialAccount($options);

        $server = $app->server;
        $user = $app->user;

        $url = domain;
        // 消息回复  技术支持QQ1500203929
        $server->push(function ($message) use ($user, $business_id) {
            Log::info($message);
            $pusher = SinglePusher::getinstance();
//           file_put_contents(PUBLIC_PATH.'/wxmessage.txt',var_export($message,true),FILE_APPEND);
//           FromUserName
            // $message['FromUserName'] // 用户的 openid
            // $message['MsgType'] // 消息类型：event, text....
            switch ($message->MsgType) {
                case 'event':
                    switch ($message->Event) {
                        case 'subscribe':
                            $this->upscribe($business_id, $message->FromUserName, 1, $message->EventKey, $pusher);
                            return '欢迎关注';
                            break;
                        case 'unsubscribe':
                            $this->upscribe($business_id, $message->FromUserName, 0, $message->EventKey, $pusher);
                            return '取消关注';
                            break;
                        case 'SCAN':
                            $eventKey = explode('_', $message->EventKey);
                            $openid = $message->FromUserName;
                            if ($eventKey[0] == 'fangke' && isset($eventKey[1])) {
                                //说明已经关注了，则直接登录就是了

                                $subscribe = 1;
                                $visiter_id = $eventKey[1];
                                $wxInfo = Db::name('weixin')->field('subscribe')->where(['business_id' => $business_id, 'open_id' => $openid])->find();
                                if (isset($wxInfo['subscribe'])) {
                                    if ($wxInfo['subscribe'] != $subscribe) {
//                        不相等则更新
                                        Db::name('weixin')->where(['business_id' => $business_id, 'open_id' => $openid])->update(['subscribe' => $subscribe]);
                                    }
                                } else {
                                    Db::name('weixin')->insert(['subscribe' => $subscribe, 'business_id' => $business_id, 'open_id' => $openid, 'subscribe_time' => 0]);
                                }
                                $channel = bin2hex($visiter_id . '/' . $business_id);
                                $pusher->trigger("cu" . $channel, "bind-wechat", array("message" => "登录成功！", 'visiter_id' => $visiter_id, 'open_id' => $openid));


                            } elseif ($eventKey[0] == 'kefu' && isset($eventKey[1])) {
                                Db::name('service')->where(['service_id' => $eventKey[1]])->update(['open_id' => $openid]);
                                $pusher->trigger("kefu" . $eventKey[1], "bind-wechat", array("message" => "绑定成功！"));
                            }
//                           array(
//   'ToUserName' => 'gh_2507885aa787',
//   'FromUserName' => 'o1PDR5pGh1XnGRsnaxgUQRNZKUOo',
//   'CreateTime' => '1614428274',
//   'MsgType' => 'event',
//   'Event' => 'SCAN',
//   'EventKey' => 'kefu_1',
//   'Ticket' => 'gQE58DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyLU9JV1lGa01maEMxaWF2Mk53Y1cAAgSKNjpgAwQA6QcA',
//)
                            return 'success';
                            break;
                    }
                    break;
                case 'text':
                    switch ($message->Content) {
                        case 'qq':
                            return 'qq:1500203929';
                            break;
                        case '价格':
                            return '价格：￥4999';
                            break;
                        case '官网':
                            return '请访问:<a href="http://www.80zx.com/">八零在线</a>提供技术驱动';
                            break;
                    }
                    break;
                default:
                    return '收到其它消息';
                    break;
            }

        });

        // 自定义菜单
//       $menu = $app->menu;
        /*       $buttons =[

                    [
                        "name"=>"客服系统",
                       "sub_button"=>[
                          [
                          "type"=>"view",
                          "name"=>"工作台",
                          "url"=>$url."/weixin/login/callback/business_id/".$business_id
                          ],
                           [
                               "type" => "view",
                               "name" => "联系客服",
                               "url"  => $url."/index/index/wechat/groupid/0/business_id/".$business_id
                           ]
                         ],
                    ],
               ];*/
//       $menu->add($buttons);
        $response = $server->serve();
        $response->send();
    }
//   关注操作
//   关注操作 技术支持QQ1500203929
    private function upscribe($business_id, $openid, $subscribe, $eventKey, $pusher)
    {
        if (!$eventKey) return false;
        $eventKey = explode('_', $eventKey);
        if (isset($eventKey[2]) && $eventKey[1] == 'kefu' && $eventKey[2]) {
            if ($subscribe) {
                //关注
                Db::name('service')->where(['service_id' => $eventKey[2]])->update(['open_id' => $openid]);
                $pusher->trigger("kefu" . $eventKey[2], "bind-wechat", array("message" => "绑定成功！"));
            } else {
                //取消关注
                Db::name('service')->where(['service_id' => $eventKey[2]])->update(['open_id' => '']);
            }

        } elseif (isset($eventKey[2]) && $eventKey[1] == 'fangke' && $eventKey[2]) {
            $visiter_id = $eventKey[2];
            $wxInfo = Db::name('weixin')->field('subscribe')->where(['business_id' => $business_id, 'open_id' => $openid])->find();
            if (isset($wxInfo['subscribe'])) {
                if ($wxInfo['subscribe'] != $subscribe) {
//                        不相等则更新
                    Db::name('weixin')->where(['business_id' => $business_id, 'open_id' => $openid])->update(['subscribe' => $subscribe, 'subscribe_time' => time()]);
                }
            } else {
                Db::name('weixin')->insert(['subscribe' => $subscribe, 'business_id' => $business_id, 'open_id' => $openid, 'subscribe_time' => time()]);
            }
            if ($subscribe) {
                $channel = bin2hex($visiter_id . '/' . $business_id);
                $pusher->trigger("cu" . $channel, "bind-wechat", array("message" => "关注公众号成功！", 'visiter_id' => $visiter_id, 'open_id' => $openid));
            }
        } elseif ($subscribe == 1) {
            //通用关注
            $wxInfo = Db::name('weixin')->field('subscribe')->where(['business_id' => $business_id, 'open_id' => $openid])->find();
            if ($wxInfo) {
                Db::name('weixin')->where(['business_id' => $business_id, 'open_id' => $openid])->update(['subscribe' => $subscribe, 'subscribe_time' => time()]);
            } else {
                Db::name('weixin')->insert(['subscribe' => $subscribe, 'business_id' => $business_id, 'open_id' => $openid, 'subscribe_time' => time()]);
            }

        } elseif ($subscribe == 0) {
            //通用取消关注
            Db::name('weixin')->where(['business_id' => $business_id, 'open_id' => $openid])->update(['subscribe' => $subscribe, 'subscribe_time' => time()]);
            Db::name('service')->where(['business_id' => $business_id, 'open_id' => $openid])->update(['open_id' => '']);
        }
    }

}