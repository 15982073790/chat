<?php


namespace app\index\controller;

use app\admin\model\RestSetting;
use app\admin\model\WechatPlatform;
use app\admin\model\WechatService;
use app\common\lib\SinglePusher;
use EasyWeChat\Factory;
use think\Controller;
use app\Common;
use think\Exception;
use think\Db;
/**
 *
 * 前台Pc端对话窗口.
 */
class Index extends Controller
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('baseroot', BASEROOT);
    }

    /**
     *
     * [home description]
     * @return [type] [description]
     */
    public function home()
    {
        $data = $this->request->param();
        if (!isset($data['business_id'])) {
            $this->error('商户ID不存在');
        }
        $setting = model("business")->field('domain_entry,domain_landing')->where('id', $data['business_id'])->find();
        if (!$setting) {
            $this->error('当前商户坐席不存在');
        }
        $domain_landing = BASEROOT;
        if ($setting['domain_landing']) {
            $domain_landing = $setting['domain_landing'];
        }
        if (isset($data['code']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            try {
                $wechat = WechatPlatform::get(['business_id' => $data['business_id']]);
                $appid = $wechat['app_id'];
                $appsecret = $wechat['app_secret'];
                $weixin = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code={$data['code']}&grant_type=authorization_code");//通过code换取网页授权access_token
                $array = json_decode($weixin, true); //对JSON格式的字符串进行编码
                //{"errcode":40029,"errmsg":"invalid code"}
                if (!isset($array['access_token'])) {
                    //说明没有获取到
                    $this->error($array['errmsg'], null, '', 999999999);
                }
                cache('oauth_access_token', $array['access_token'], 7000);

                $info = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token={$array['access_token']}&openid={$array['openid']}&lang=zh_CN");
                $infoarray = json_decode($info, true);
                if (!isset($infoarray['openid'])) {
                    //说明没有获取到
                    $this->error('当前会员信息获取失败', null, '', 999999999);
                }
                $data['visiter_id'] = $infoarray['openid'];
                $common = new Common();
                $data['visiter_name'] = $common->remove_emoji($infoarray['nickname']);
                $data['avatar'] = $infoarray['headimgurl'];
                if (!isset($data['groupid'])) {
                    $data['groupid'] = 0;
                }
                $this->wechat_platform = Db::name('wechat_platform')->where(['business_id' => $data['business_id']])->find();
                if ($this->wechat_platform && $this->wechat_platform['app_id'] && $this->wechat_platform['app_secret'] && $this->wechat_platform['isscribe']) {
                    //https://api.weixin.qq.com/cgi-bin/user/info?access_token={$this->access_token}&openid={$openid}
                    //https://api.weixin.qq.com/cgi-bin/user/info?access_token=
                    $options = [
                        'app_id' => $this->wechat_platform['app_id'],
                        'secret' => $this->wechat_platform['app_secret'],
                        'aes_key' => $this->wechat_platform['wx_aeskey'],
                        'token' => $this->wechat_platform['wx_token'],
                    ];
                    $app = Factory::officialAccount($options);
                    $user = $app->user->get($data['visiter_id']);
                    $wxInfo = Db::name('weixin')->field('subscribe')->where(['business_id' => $data['business_id'], 'open_id' => $data['visiter_id']])->find();
                    $subscribe = $user['subscribe'];
                    if (isset($wxInfo['subscribe'])) {
                        if ($wxInfo['subscribe'] != $subscribe) {
//                        不相等则更新
                            Db::name('weixin')->where(['business_id' => $data['business_id'], 'open_id' => $data['visiter_id']])->update(['subscribe' => $subscribe]);
                        }
                    } else {
                        Db::name('weixin')->insert(['subscribe' => $subscribe, 'business_id' => $data['business_id'], 'open_id' => $data['visiter_id'], 'subscribe_time' => 0]);
                    }
                }
            } catch (Throwable $t) {
                $this->error($t->getMessage(), null, '', 999999999);
            } catch (Exception $e) {
                $this->error($e->getMessage(), null, '', 999999999);
            }

        } else {
            session('from_url', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
        }
        $common = new Common();
        $newstr = $common->encrypt(http_build_query($data), 'E', YMWL_SALT);

        hook('homejumpbeforehook', array_merge($data, ['code' => $newstr]));
        $this->redirect($domain_landing . '/index/index?code=' . $newstr);

    }

    /**
     * 对话窗口页面.
     *
     * @return mixed
     */
    public function index()
    {
        $arr = $this->request->get();
        $arr['code'] = $this->request->get('code', '', null);
        $common = new Common();
        $is_mobile = $common->isMobile();
        if ($arr['code'] == '') {
            exit('error');
        }

        if ($is_mobile) {
            $this->redirect(BASEROOT . '/mobile/index?code=' . urlencode($arr['code']));
        }
        $data = $common->encrypt($arr['code'], 'D', YMWL_SALT);

        if (!$data) {
            $this->redirect(BASEROOT . '/index/index/errors');
        }
        parse_str($data, $arr2);
        if (!isset($arr2['business_id'])) {
            $this->error('商户ID不存在');
        }
        $special = isset($arr2['special']) ? $arr2['special'] : null;
        $theme = isset($arr2['theme']) ? $arr2['theme'] : '25c16f';
        $arr2['product'] = isset($arr2['product']) ? $arr2['product'] : null;
        $pusher = SinglePusher::getinstance();
        $url = domain;
        $from_url = session('from_url');
        if (!$from_url) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $from_url = $_SERVER['HTTP_REFERER'];
            } else {
                $from_url = '';
            }
        }
        $business_id = isset($arr2['business_id']) ? htmlspecialchars($arr2['business_id']) : '';
        $visiter_id = isset($arr2['visiter_id']) ? $arr2['visiter_id'] : '';
        if ($visiter_id === '') {
            $visiter_id = cookie('visiter_id');
            if (!$visiter_id) {
                $visiter_id = $common->getvid();
                //采用浏览器保存更持久  QQ1500203929
                cookie('visiter_id', $visiter_id, 63072000);
            }
        }

        // 判断是否访问过
        if ($visiter_id) {

            if (!cookie('product_id')) {

                if ($arr2['product'] != NULL) {
                    $content = json_decode($arr2['product'], true);
                    if (isset($content['pid']) && isset($content['url']) && isset($content['img']) && isset($content['title']) && isset($content['info']) && isset($content['price'])) {
                        setcookie("product_id", $content['pid'], time() + 3600 * 12);
                        $arr2['timestamp'] = time();
                        $service = model('queue')->where(['visiter_id' => $visiter_id, 'business_id' => $business_id])->find();
                        if ($service) {
                            $service_id = $service['service_id'];
                        } else {
                            $service_id = 0;
                        }
                        $str = '<a href="' . $content['url'] . '" target="_blank" class="wolive_product">';
                        $str .= '<div class="wolive_img"><img src="' . $content['img'] . '" width="100px"></div>';
                        $str .= '<div class="wolive_head"><p class="wolive_info">' . $content['title'] . '</p><p class="wolive_price">' . $content['price'] . '</p>';
                        $str .= '<p class="wolive_info">' . $content['info'] . '</p>';
                        $str .= '</div></a>';
                        $mydata = ['service_id' => $service_id, 'visiter_id' => $visiter_id, 'content' => $str, 'timestamp' => time(), 'business_id' => $business_id, 'direction' => 'to_service'];

                        $pusher->trigger('kefu' . $service_id, 'cu-event', array('message' => $mydata));
                        $chats = model('chats')->insert($mydata);
                    }

                }
            } else {
                $pid = cookie('product_id');
                $pid = $pid ? $pid : '';
                if ($arr2['product'] != NULL) {
                    $content = json_decode($arr2['product'], true);
                    if (isset($content['pid']) && isset($content['url']) && isset($content['img']) && isset($content['title']) && isset($content['info']) && isset($content['price']) && $content['pid'] != $pid) {
                        $service = model('queue')->where(['visiter_id' => $visiter_id, 'business_id' => $business_id])->find();

                        if ($service) {
                            $service_id = $service['service_id'];
                        } else {
                            $service_id = 0;
                        }
                        $str = '<a href="' . $content['url'] . '" target="_blank" class="wolive_product">';
                        $str .= '<div class="wolive_img"><img src="' . $content['img'] . '" width="100px"></div>';
                        $str .= '<div class="wolive_head"><p class="wolive_info">' . $content['title'] . '</p><p class="wolive_price">' . $content['price'] . '</p>';
                        $str .= '<p class="wolive_info">' . $content['info'] . '</p>';
                        $str .= '</div></a>';
                        $mydata = ['service_id' => $service_id, 'visiter_id' => $visiter_id, 'content' => $str, 'timestamp' => time(), 'business_id' => $business_id, 'direction' => 'to_service'];
                        $pusher->trigger('kefu' . $service_id, 'cu-event', array('message' => $mydata));
                        $chats = model('chats')->insert($mydata);

                    }
                }
            }

        } else {

            if (!cookie('product_id')) {

                if ($arr2['product'] != NULL) {
                    $content = json_decode($arr2['product'], true);
                    if (isset($content['pid']) && isset($content['url']) && isset($content['img']) && isset($content['title']) && isset($content['info']) && isset($content['price'])) {
                        setcookie("product_id", $content['pid'], time() + 3600 * 12);
                        $arr2['timestamp'] = time();

                        $service = model('queue')->where(['visiter_id' => $visiter_id, 'business_id' => $business_id])->find();
                        if ($service) {
                            $service_id = $service['service_id'];
                        } else {
                            $service_id = 0;
                        }
                        $str = '<a href="' . $content['url'] . '" target="_blank" class="wolive_product">';
                        $str .= '<div class="wolive_img"><img src="' . $content['img'] . '" width="100px"></div>';
                        $str .= '<div class="wolive_head"><p class="wolive_info">' . $content['title'] . '</p><p class="wolive_price">' . $content['price'] . '</p>';
                        $str .= '<p class="wolive_info">' . $content['info'] . '</p>';
                        $str .= '</div></a>';
                        $mydata = ['service_id' => $service_id, 'visiter_id' => $visiter_id, 'content' => $str, 'timestamp' => time(), 'business_id' => $business_id, 'direction' => 'to_service'];
                        $pusher->trigger('kefu' . $service_id, 'cu-event', array('message' => $mydata));
                        $chats = model('chats')->insert($mydata);
                    }

                }
            } else {
                if ($arr2['product'] != NULL) {
                    if ($arr2['visiter_id'] != cookie('visiter_id')) {
                        $content = json_decode($arr2['product'], true);
                        if (isset($content['pid']) && isset($content['url']) && isset($content['img']) && isset($content['title']) && isset($content['info']) && isset($content['price'])) {
                            $service = model('queue')->where(['visiter_id' => $visiter_id, 'business_id' => $business_id])->find();
                            if ($service) {
                                $service_id = $service['service_id'];
                            } else {
                                $service_id = 0;
                            }
                            $str = '<a href="' . $content['url'] . '" target="_blank" class="wolive_product">';
                            $str .= '<div class="wolive_img"><img src="' . $content['img'] . '" width="100px"></div>';
                            $str .= '<div class="wolive_head"><p class="wolive_info">' . $content['title'] . '</p><p class="wolive_price">' . $content['price'] . '</p><p>';
                            $str .= '<p class="wolive_info">' . $content['info'] . '</p>';
                            $str .= '</div></a>';
                            $mydata = ['service_id' => $service_id, 'visiter_id' => $visiter_id, 'content' => $str, 'timestamp' => time(), 'business_id' => $business_id, 'direction' => 'to_service'];
                            $pusher->trigger('kefu' . $service_id, 'cu-event', array('message' => $mydata));
                            $chats = model('chats')->insert($mydata);
                        }
                    } else {
                        $pid = cookie('product_id');
                        $product = $arr2['product'];
                        $content = json_decode($arr2['product'], true);
                        // 判断是否是同个商品
                        if (isset($content['pid']) && isset($content['url']) && isset($content['img']) && isset($content['title']) && isset($content['info']) && isset($content['price']) && $content['pid'] != $pid) {
                            $service = model('queue')->where(['visiter_id' => $visiter_id, 'business_id' => $business_id])->find();
                            if ($service) {
                                $service_id = $service['service_id'];
                            } else {
                                $service_id = 0;
                            }
                            $str = '<a href="' . $content['url'] . '" target="_blank" class="wolive_product">';
                            $str .= '<div class="wolive_img"><img src="' . $content['img'] . '" width="100px"></div>';
                            $str .= '<div class="wolive_head"><p class="wolive_info">' . $content['title'] . '</p><p class="wolive_price">' . $content['price'] . '</p>';
                            $str .= '<p class="wolive_info">' . $content['info'] . '</p>';
                            $str .= '</div></a>';
                            $mydata = ['service_id' => $service_id, 'visiter_id' => $visiter_id, 'content' => $str, 'timestamp' => time(), 'business_id' => $business_id, 'direction' => 'to_service'];
                            $pusher->trigger('kefu' . $service_id, 'cu-event', array('message' => $mydata));
                            $chats = model('chats')->insert($mydata);
                        }
                    }

                }
            }
        }

        $channel = bin2hex($visiter_id . '/' . $business_id);
        $visiter_name = isset($arr2['visiter_name']) ? htmlspecialchars($arr2['visiter_name']) : '';
        $avatar = isset($arr2['avatar']) ? htmlspecialchars($arr2['avatar']) : '';
        $groupid = isset($arr2['groupid']) ? $arr2['groupid'] : 0;

        if ($visiter_name == '') {
            $visiter_name = '游客' . $visiter_id;
        }

        $app_key = app_key;
        $whost = whost;
        $arr = parse_url($whost);
        if ($arr['scheme'] == 'ws') {

            $port = 'wsPort';
            $value = 'false';
        } else {

            $value = 'true';
            $port = 'wssPort';
        }
        session('from_url', null);
        $business = model('business')->where('id', $business_id)->find();
        $rest = RestSetting::get(['business_id' => $business_id]);
        $state = empty($rest) ? false : $rest->isOpen($business_id, $visiter_id);
        if (!$avatar || !$visiter_name) {
            $visiterInfo = Db::name('visiter')->field('visiter_name,avatar')->where(['visiter_id' => $visiter_id, 'business_id' => $business_id])->find();
            if ($visiterInfo) {
                $avatar = $avatar == '' ? $visiterInfo['avatar'] : '';
                $visiter_name = $visiter_name == '' ? $visiterInfo['visiter_name'] : '';
            }

        }

        $is_bind_wechat = 0;

        $this->wechat_platform = Db::name('wechat_platform')->where(['business_id' => $business_id])->find();
        if ($this->wechat_platform && $this->wechat_platform['app_id'] && $this->wechat_platform['app_secret'] && $this->wechat_platform['isscribe']) {
            $wxInfo = Db::name('weixin')->field('subscribe')->where(['business_id' => $business_id, 'open_id' => $visiter_id])->find();
            if (!$wxInfo || $wxInfo['subscribe'] == 0) {
                $is_bind_wechat = 1;
            }
        }


        $this->assign('reststate', $state);
        $this->assign('restsetting', $rest);
        $this->assign('business_name', $business['business_name']);
        $this->assign("type", $business['video_state']);
        $this->assign("atype", $business['audio_state']);
        $this->assign('app_key', $app_key);
        $this->assign('whost', $arr['host']);
        $this->assign('value', $value);
        $this->assign('wport', wport);
        $this->assign('port', $port);
        $this->assign('url', $url);
        $this->assign('groupid', $groupid);
        $this->assign('visiter', $visiter_name);
        $this->assign('business_id', $business_id);
        $this->assign('from_url', $from_url);
        $this->assign('channel', $channel);
        $this->assign('visiter_id', $visiter_id);
        $this->assign('avatar', $avatar);
        $this->assign('theme', $theme);
        $this->assign('is_bind_wechat', $is_bind_wechat);
        $this->assign('special', $special);
        return $this->fetch();
    }
//技术支持QQ1500203929
    public function qrcode()
    {

        $visiter_id = $this->request->post('visiter_id', '');
        $business_id = $this->request->post('business_id', '');
        if ($visiter_id && $business_id) {
            $qrcode = WechatService::get($business_id)->qrcode;
            $result = $qrcode->temporary('fangke_' . $visiter_id, 6 * 24 * 3600);
            $ticket = $result['ticket'];
            $url = $qrcode->url($ticket);
            return json(['code' => 1, 'data' => $url]);
        } else {
            return json(['code' => 0, 'msg' => '访客信息没有获取到！']);
        }
    }
//    技术支持QQ1500203929
    public function bind_user()
    {
        $visiter_id = $this->request->post('visiter_id', '');
        $open_id = $this->request->post('open_id', '');
        if (!$visiter_id || !$open_id) {
            return json(['code' => 0, 'msg' => '绑定失败']);
        }
        $business_id = $this->request->post('business_id', '');
        $code = $this->request->post('code', '');
        $parameter = $this->request->post('parameter', '', null);
        $setting = model("business")->field('domain_entry,domain_landing')->where('id', $business_id)->find();
        $domain_landing = BASEROOT;
        if (!$setting) {
            return json(['code' => 0, 'msg' => '当前商户坐席不存在']);
        }
        if ($setting['domain_landing']) {
            $domain_landing = $setting['domain_landing'];
        }
        $wechat = WechatPlatform::get(['business_id' => $business_id]);
        // config配置
        $options = [
            'app_id' => $wechat['app_id'],
            'secret' => $wechat['app_secret'],
            'aes_key' => $wechat['wx_aeskey'],
            'token' => $wechat['wx_token'],
        ];
        $app = Factory::officialAccount($options);
        $user = $app->user->get($open_id);
        if (!$user['subscribe']) {
            return json(['code' => 0, 'msg' => '请先关注微信公众号']);
        }
        $url = '';
        if ($code) {
            $common = new Common();
            $data1 = $common->encrypt($code, 'D', YMWL_SALT);
            parse_str($data1, $data);
            $data['visiter_id'] = $open_id;
            $data['avatar'] = $user['headimgurl'];
            $data['visiter_name'] = $user['nickname'];
            $str = http_build_query($data);
            $newstr = $common->encrypt($str, 'E', YMWL_SALT);
            $url = $domain_landing . '/index/index?code=' . $newstr;
        } elseif ($parameter) {
            $data = json_decode($parameter, true);
            $data['visiter_id'] = $open_id;
            $data['avatar'] = $user['headimgurl'];
            $data['visiter_name'] = $user['nickname'];
            $url = $domain_landing . '/layer?' . http_build_query($data);
        }

        $res = Db::name('visiter')->field('vid')->where(['visiter_id' => $open_id, 'business_id' => $business_id])->find();
        if (!$res) {
            Db::name('visiter')->where(['visiter_id' => $visiter_id, 'business_id' => $business_id])->update(['visiter_id' => $open_id, 'visiter_name' => $user['nickname'], 'avatar' => $user['headimgurl'], 'channel' => bin2hex($open_id . '/' . $business_id)]);
        }
        cookie('visiter_id', $open_id, 63072000);
        Db::name('chats')->where(['visiter_id' => $visiter_id, 'business_id' => $business_id])->update(['visiter_id' => $open_id]);
        return json(['code' => 1, 'msg' => '绑定成功', 'url' => $url]);
    }
    /**
     * 404页面
     */

    public function errors()
    {
        return $this->fetch();
    }

    /**
     * 获取排队数量.
     *
     * @return mixed
     */
    public function getwaitnum()
    {
        $post = $this->request->post();
        $num = model('queue')->where('visiter_id', $post['visiter_id'])->where("service_id", 0)->count();
        return $num;
    }

    public function wechat()
    {
        $business_id = $this->request->param('business_id', '');
        $group_id = $this->request->param('groupid', 0);
        $special = $this->request->param('special', '');
        $theme = $this->request->param('theme', '25c16f');
        if (empty($business_id)) {
            abort(500);
        }
        session('from_url', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
        $wechat = WechatPlatform::get(['business_id' => $business_id]);
        $APPID = $wechat['app_id'];
        $REDIRECT_URI = url('index/index/home', ['business_id' => $business_id, 'groupid' => $group_id, 'special' => $special, 'theme' => $theme], true, true);
        $scope = 'snsapi_userinfo';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $APPID . '&redirect_uri=' . urlencode($REDIRECT_URI) . '&response_type=code&scope=' . $scope . '&state=123#wechat_redirect';
        $this->redirect($url);
    }
    public function test()
    {
    }
}