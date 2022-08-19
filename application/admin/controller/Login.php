<?php


namespace app\admin\controller;

use app\common\lib\SinglePusher;
use app\platform\model\Business;
use app\platform\model\Option;
use think\Controller;
use think\captcha\Captcha;
use think\config;
use think\Db;
use app\Common;
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
        $this->assign('baseroot', BASEROOT);
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
            $this->redirect(url('admin/index/index'));
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
        $option = Option::getList('regist', 0, 'admin');
        $this->assign('regist', $option['regist']);
        $this->assign('submit', url('check', $params));
        return $this->fetch();
    }

    /**
     * 注册页面.
     *
     * @return mixed
     */
    public function sign()
    {
        $business = [];
        if ($this->business_id) {
            $business = Business::get($this->business_id);

        }
        $this->assign('business', $business);
        return $this->fetch();
    }
    /**
     *  注册用户.
     *
     * @return string
     */
    public function regist()
    {
        $option = Option::getList('regist,regist_expire,regist_crnum', 0, 'admin');
        if (!isset($option['regist']) || $option['regist'] == 0) {
            return $this->error("系统禁止注册");
        }
        $post = $this->request->post();
        $result = $this->validate($post, 'Login.regist');
        if (true !== $result) {
            return $this->error($result);
        }
        $mService = model('service');
        $res = $mService->where('user_name', $post['user_name'])->find();
        if ($res) {
            return $this->error("用户名已存在！");
        }
        $res = model('business')->where('business_name', $post['business_name'])->find();
        if ($res) {
            return $this->error("商家名已存在！");
        }
        //合成新函数
        unset($post['captcha']);
        unset($post['repassword']);
        $exp_time = $option['regist'] ? $option['regist'] * 24 * 60 * 60 : 7 * 24 * 60 * 60;
        $post['admin_id'] = 1;
        $post['max_count'] = $option['regist_crnum'] ? $option['regist_crnum'] : 1;
        $post['expire_time'] = time() + $exp_time;
        $res = Business::addBusiness($post);
        if ($res) {
            return $this->success('注册成功');
        } else {
            return $this->error('注册失败');
        }

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
        $post['user_name'] = $post['username'];
        unset($post['username']);
        $result = $this->validate($post, 'Login.login');
        if ($result !== true) {
            $this->error($result);
        }
        // 获取信息 根据$post['username'] 的数据 来做条件 获取整条信息
//                        ->where('business_id',$post['business_id'])
        /* $admin = model("service")
             ->where('user_name', $post['user_name'])
             ->find();
         if (!$admin) {
             $this->error("用户不存在");
         }*/
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
        model('service')->where('service_id', $login['service_id'])->update(['state' => 'online']);
//            $data = model('service')->where('service_id', $login['service_id'])->find();

        session('Msg', $login);
        $business = Business::get(session('Msg.business_id'));
        session('Msg.business', $business->getData());
        $common = new Common();
        $expire = 7 * 24 * 60 * 60;
        $service_token = $common->cpEncode($login['user_name'], YMWL_SALT, $expire);
        Cookie::set('service_token', $service_token, $expire);
        session('Platform.referer', null);
        $ismoblie = $common->isMobile();
        $ip = $this->request->ip();
        $login_side = $ismoblie ? 2 : 1;
        $area = \app\common\iplocation\Ip::find($ip);
        @Db::name('login_log')->insert([
            'uid' => $login['service_id'],
            'name' => $login['user_name'],
            'ip' => $ip,
            'source' => 2,
            'area' => $area[0] . $area[1] . $area[2] . $area[3],
            'login_side' => $login_side,
            'createtime' => time()
        ]);
        if ($ismoblie) {
            $this->success('登录成功', url("mobile/admin/index"));
        } else {
            $this->success('登录成功', url("admin/Index/index"));
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
        $this->redirect(url('admin/login/index', ['business_id' => $this->request->param('business_id')]));

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