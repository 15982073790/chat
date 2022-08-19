<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/1/28
 * Time: 16:34
 */
namespace app\platform\controller;

use app\platform\model\Option;
use app\platform\service\Auth;
use app\platform\service\Menu;
use app\platform\service\Permissions;
use think\Controller;
use think\Hook;

/**
 * @property Auth $auth
 */
class Base extends Controller
{

    /**
     * 布局模板
     * @var string
     */
    protected $layout = 'default';

    protected $admin = null;

    protected $auth = null;

    protected $option = null;

    public function _initialize()
    {
        $this->auth = Auth::instance();
        $modulename = strtolower($this->request->module());
        $controllername = strtolower($this->request->controller());
        $actionname = strtolower($this->request->action());
        $route = $modulename . '/' . $controllername . '/' . $actionname;
        $token = $this->request->server('HTTP_TOKEN', $this->request->param('token', \think\Cookie::get('token')));
        $path = str_replace('.', '/', $controllername) . '/' . $actionname;
        // 设置当前请求的URI
        $this->auth->setRequestUri($path);

        Hook::add('action_begin', 'app\\platform\\behavior\\Permission');

        // 检测是否需要验证登录
        if (!$this->auth->match($this->noNeedLogin)) {
            //初始化
            $this->auth->init($token);
            //检测是否登录
            if (!$this->auth->isLogin()) {
                $this->redirect(url('passport/login'));
            }
            $this->admin = $this->auth->getUserinfo();
            $menu = Menu::getMenus();
            $title = array_column($menu, null, 'route');
            $this->assign('title', isset($title[$route]['name']) ? $title[$route]['name'] : null);
            $this->assign('admin', $this->admin);
            $this->assign('menu', $menu);
        } else {
            // 如果有传递token才验证是否登录状态
            if ($token) {
                $this->auth->init($token);
            }
        }
        $this->assign('route', $route);
        $this->view->engine->layout('layout/' . $this->layout);

        //设置seo
        $option = Option::getList('title,logo,copyright,max_login_error,passport_bg,open_register', 0, 'admin');
        $this->option = $option;
        $this->assign('option', $option);
        parent::_initialize();
    }

}