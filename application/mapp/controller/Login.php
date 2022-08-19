<?php


namespace app\mapp\controller;

use app\admin\model\Admins;
use app\common\lib\SinglePusher;
use app\platform\enum\apps;
use app\platform\model\Business;
use think\Controller;
use think\captcha\Captcha;
use think\config;
use app\Common;
use app\extra\push\Pusher;
use think\Cookie;


/**
 * 登录控制器.
 */
class Login extends Controller
{
    private $business_id = null;

    public function _initialize()
    {
        $this->business_id = $this->request->param('business_id', Cookie::get('YMWL_APP_FLAG'));

        if (!empty($this->business_id)) {
            Cookie::set('YMWL_APP_FLAG', $this->business_id);
        }

        $this->assign('business_id', $this->business_id);
    }

    /**
     * 登陆首页.
     *
     * @return string
     */
    public function index()
    {
        $token = Cookie::get('service_token');
        if ($token) {
            $this->redirect(url('admin/index'));
        }
        // 未登陆，呈现登陆页面.
        $params = [];
        $goto = $this->request->get('goto', '');
        if ($goto) {
            $params['goto'] = urlencode($goto);
        }
        $business = [];
        if ($this->business_id) {
            $business = Business::get($this->business_id);

        }
        $this->assign('business', $business);
        $this->assign('submit', url('check', $params));
        return $this->fetch();
    }

    /**
     * 注册页面.
     *
     * @return mixed
     */
    private function sign()
    {
        return $this->fetch();
    }


    /**
     * 验证码.
     *
     * @return \think\Response
     */
    public function captcha()
    {

        $captcha = new Captcha(Config::get('captcha'));
        ob_clean();
        return $captcha->entry('admin_login');
    }

    /**
     * 注册验证码.
     *
     * @return \think\Response
     */
    public function captchaForAdmin()
    {
        $captcha = new Captcha(Config::get('captcha'));
        return $captcha->entry('admin_regist');
    }

    /**
     * 登录检查.
     *
     * @return void
     */
    public function check()
    {
        $post = $this->request->post();
//        if(!isset($post['username']) || !isset($post['password']) || !isset($post['business_id'])){
        if (!isset($post['username']) || !isset($post['password'])) {
            $this->error('参数不完整!', url("/admin/login/index"));
        }

        $post['user_name'] = htmlspecialchars($post['username']);

        $post["password"] = htmlspecialchars($post['password']);
        unset($post['username']);

        $result = $this->validate($post, 'Login');
        if ($result !== true) {
            $this->error($result);
        }
        // 密码检查

        $pass = md5($post['user_name'] . "hjkj" . $post['password']);

        $admin = model("service")
            ->where('user_name', $post['user_name'])
            ->where('password', $pass)
            ->find();

        if (!$admin) {

            $this->error('登录用户名或密码错误');
        }

        // 获取登陆数据

        $login = $admin->getData();

        // 删掉登录用户的敏感信息
        unset($login['password']);

        $res = model('service')->where('service_id', $login['service_id'])->update(['state' => 'online']);

//            $data = model('service')->where('service_id', $login['service_id'])->find();


        session('Msg', $login);
        $business = Business::get(session('Msg.business_id'));
        session('Msg.business', $business->getData());

        $common = new Common();
        $expire = 7 * 24 * 60 * 60;
        $service_token = $common->cpEncode($login['user_name'], YMWL_SALT, $expire);
        Cookie::set('service_token', $service_token, $expire);

        $ismoblie = $common->isMobile();


        $this->success('登录成功', url("admin/index"));


    }

    /**
     *  注册用户.
     *
     * @return string
     */
    private function regist()
    {

        $post = $this->request->post();

        $post['user_name'] = htmlspecialchars($post['user_name']);
        $post["password"] = htmlspecialchars($post['password']);
        $post["password2"] = htmlspecialchars($post['password2']);
        $post['nick_name'] = "管理员" . $post['user_name'];
        unset($post['username']);
        unset($post['nickname']);

        $result = $this->validate($post, 'Admins');

        if ($result !== true) {

            return $result;
        }

        $res = model('service')
            ->where('user_name', $post['user_name'])
            ->where()
            ->find();

        if ($res) {
            return "用户名存在！";
        }
        //合成新函数
        $post['business_id'] = $post['user_name'];

        unset($post['captcha']);
        unset($post['password2']);

        $pass = md5($post['user_name'] . "hjkj" . $post['password']);
        $post['password'] = $pass;
        $post['level'] = 'manager';


        $debug = model('service')->insert($post);
        if ($debug) {
            $arr = [];
            $arr['business_id'] = $post['user_name'];
            $business = model('business')->insert($arr);
            return '注册成功';
        }
    }


    /**
     * 退出登陆 并清除session.
     *
     * @return void
     */
    public function logout()
    {
        Cookie::delete('service_token');
        setCookie("cu_com", "", time() - 60);
        session('Msg', null);

        $this->redirect(url('login/index', ['business_id' => $this->request->param('business_id')]));

    }

    /**
     * socket_auth 验证
     * [auth description]
     * @return [type] [description]
     */
    public function auth()
    {
        $pusher = SinglePusher::getinstance();
        $data = $pusher->socket_auth($_POST['channel_name'], $_POST['socket_id']);
        return $data;
    }

}