<?php


namespace app\mobile\controller;

use app\admin\model\RestSetting;
use app\common\lib\SinglePusher;
use think\Controller;
use app\Common;
use think\Db;

/**
 *
 * 前台手机端控制器.
 * Class Index
 * @package app\mobile\controller
 */
class Index extends Controller
{
    public function _initialize()
    {
        $this->assign('baseroot', BASEROOT);
        parent::_initialize();
    }

    public function report()
    {
        $reporttype = config('setting.reporttype');
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($data['business_id'] < 1) {
                $this->error('提交来源非法！');
            }
            if (!isset($reporttype[$data['type']])) {
                $this->error('不存在的投诉类型！');
            }
            $data['createtime'] = time();
            $data['ip'] = $this->request->ip();
            model('report')->allowField(true)->insert($data);
            $this->success('感谢你的反馈，我们会尽快处理！');
        }
        $this->assign('type', $reporttype);
        $this->assign('business_id', $this->request->param('business_id'));
        return $this->fetch();
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
     * [home description]
     * @return [type] [description]
     */
    public function home()
    {

        $data = $this->request->param();
        $common = new Common();
        $newstr = $common->encrypt(http_build_query($data), 'E', YMWL_SALT);
        $this->redirect(BASEROOT . '/mobile/index?code=' . $newstr);

    }


    /**
     *
     * 手机端首页.
     *
     * @return mixed
     */
    public function index()
    {


        $url = domain;
        $arr = $this->request->get();

        $common = new Common();
        if (!isset($arr['code'])) {
            $arr['code'] = $this->request->param('code', '');
            $arr['code'] = urldecode($arr['code']);
        }
        $data = $common->encrypt($arr['code'], 'D', YMWL_SALT);

        if (!$data) {
//            var_dump($data,$arr);exit();
            $this->redirect(BASEROOT . '/index/index/errors');
        }
        $code = $arr['code'];
        parse_str($data, $arr2);
        $setting = model("business")->field('domain_entry,domain_landing')->where('id', $arr2['business_id'])->find();
        if (!$setting) {
            $this->error('当前商户坐席不存在', null, '', 999999999);
        }
        if ($setting['domain_landing']) {
            $domain_landing = $setting['domain_landing'];
            if (isset($_SERVER['HTTP_HOST']) && parse_url($domain_landing)["host"] != $_SERVER['HTTP_HOST']) {
                $this->redirect($domain_landing . $_SERVER['REQUEST_URI']);
            }
        }
        $special = isset($arr2['special']) ? $arr2['special'] : null;
        $visiter_id = isset($arr2['visiter_id']) ? $arr2['visiter_id'] : '';
        $arr2['product'] = isset($arr2['product']) ? $arr2['product'] : '';

        $theme = isset($arr2['theme']) ? $arr2['theme'] : '25c16f';
        $visiter_name = isset($arr2['visiter_name']) ? htmlspecialchars($arr2['visiter_name']) : '';
        $avatar = isset($arr2['avatar']) ? htmlspecialchars($arr2['avatar']) : '';
        $groupid = isset($arr2['groupid']) ? htmlspecialchars($arr2['groupid']) : 0;
        hook('mobileindexhook', array_merge($arr, $arr2));
        $pusher = SinglePusher::getinstance();
        $business_id = $arr2['business_id'];
        if (trim($visiter_id) == '') {
            $visiter_id = cookie('visiter_id');
            if (!$visiter_id) {
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
                    $product = $arr2['product'];
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
                    $content = json_decode(htmlspecialchars_decode($arr2['product']), true);

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

                        model('chats')->insert($mydata);
                    }
                }
            } else {

                $pid = cookie('product_id');
                if ($arr2['product']) {
                    $content = json_decode(htmlspecialchars_decode($arr2['product']), true);

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

                        model('chats')->insert($mydata);
                    }
                }
            }
        }


        $channel = bin2hex($visiter_id . '/' . $business_id);
        $from_url = session('from_url');
        if (!$from_url) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $from_url = $_SERVER['HTTP_REFERER'];
            } else {
                $from_url = '';
            }
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

        $rest = RestSetting::get(['business_id' => $business_id]);
        $state = empty($rest) ? false : $rest->isOpen($business_id, $visiter_id);
        session('from_url', null);
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
        $this->assign('reststate', $state);
        $this->assign('code', $code);
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
        return $this->fetch();
    }

}