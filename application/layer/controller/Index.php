<?php

namespace app\layer\controller;

use app\admin\model\RestSetting;
use app\Common;
use app\common\lib\SinglePusher;
use think\Controller;
use app\index\model\User;
use think\Db;

/**
 *
 */
class Index extends Controller
{
    public function _initialize()
    {
        $this->assign('baseroot', BASEROOT);
        parent::_initialize();
    }

    /**
     * 唯一随机数方法
     * [rand description]
     * @param  [type] $len [description]
     * @return [type]      [description]
     */
    public function rand($len)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $string = substr(time(), -3);
        for (; $len >= 1; $len--) {
            $position = rand() % strlen($chars);
            $position2 = rand() % strlen($string);
            $string = substr_replace($string, substr($chars, $position, 1), $position2, 0);
        }
        return $string;
    }
    /**
     *
     * 手机端首页.
     *
     * @return mixed
     */
    public function index()
    {

        $this->comchat();
        return $this->fetch();
    }
//    提取出公共的聊天处理业务流程qq1500203929
    protected function comchat()
    {
        $url = domain;
        $arr2 = $this->request->get();
        if (!isset($arr2['business_id'])) {
            $this->error('商户ID不存在');
        }
        $special = isset($arr2['special']) ? $arr2['special'] : null;
        $theme = isset($arr2['theme']) ? $arr2['theme'] : null;
        $pusher = SinglePusher::getinstance();
        $business_id = $arr2['business_id'];
        $visiter_id = isset($arr2['visiter_id']) ? $arr2['visiter_id'] : '';
        $arr2['product'] = isset($arr2['visiter_id']) ? htmlspecialchars_decode($arr2['product']) : '';
        $visiter_name = isset($arr2['visiter_name']) ? htmlspecialchars($arr2['visiter_name']) : '';
        $avatar = isset($arr2['avatar']) ? $arr2['avatar'] : '';
        $groupid = isset($arr2['groupid']) ? $arr2['groupid'] : '';

        if (trim($visiter_id) == '') {
            $visiter_id = cookie('visiter_id');
            if (!$visiter_id) {
                $common = new Common();
                $visiter_id = $common->getvid();
                //采用浏览器保存更持久  QQ1500203929
                cookie('visiter_id', $visiter_id, 63072000);
            }
        } else {
            cookie('visiter_id', $visiter_id, 63072000);
        }


        if ($visiter_id) {
            if (!cookie('product_id')) {
                // 没有product_id
                if ($arr2['product']) {
                    $content = json_decode(htmlspecialchars_decode($arr2['product']), true);
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
                if ($arr2['product']) {
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
                // 没有product_id
                if ($arr2['product']) {
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
                if ($arr2['product']) {
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
        }
        $channel = bin2hex($visiter_id . '/' . $business_id);
        if (isset($_SERVER['HTTP_REFERER'])) {
            $from_url = $_SERVER['HTTP_REFERER'];
        } else {
            $from_url = '';
        }

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
        $business = model('business')->where('id', $business_id)->find();
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
        $this->assign('is_bind_wechat', $is_bind_wechat);
        $rest = RestSetting::get(['business_id' => $business_id]);
        $state = empty($rest) ? false : $rest->isOpen($business_id, $visiter_id);
        $this->assign('reststate', $state);
        $this->assign('restsetting', $rest);
        $this->assign('business_name', $business['business_name']);
        $this->assign("atype", $business['audio_state']);
        $this->assign('groupid', $groupid);
        $this->assign('app_key', $app_key);
        $this->assign('whost', $arr['host']);
        $this->assign('value', $value);
        $this->assign('wport', wport);
        $this->assign('port', $port);
        $this->assign('url', $url);
        $this->assign('visiter', $visiter_name);
        $this->assign('business_id', $business_id);
        $this->assign('from_url', $from_url);
        $this->assign('channel', $channel);
        $this->assign('visiter_id', $visiter_id);
        $this->assign('avatar', $avatar);
        $this->assign('special', $special);
        $this->assign('theme', $theme);
    }
}