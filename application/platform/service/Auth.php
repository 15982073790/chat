<?php

namespace app\platform\service;

use app\common\lib\Random;
use app\common\lib\Token;
use app\platform\model\Admin;
use think\Config;
use think\Db;
use think\Hook;
use think\Request;

class Auth
{

    protected static $instance = null;
    protected $_error = '';
    protected $_logined = FALSE;
    protected $_user = NULL;
    protected $_token = '';
    //Token默认有效时长
    protected $keeptime = 604800;
    protected $requestUri = '';
    protected $rules = [];
    //默认配置
    protected $config = [];
    protected $options = [];
    protected $allowFields = ['id', 'username', 'mobile', 'app_max_count', 'expire_time', 'permission'];

    public function __construct($options = [])
    {
        if ($config = Config::get('user')) {
            $this->config = array_merge($this->config, $config);
        }
        $this->options = array_merge($this->config, $options);
    }

    /**
     *
     * @param array $options 参数
     * @return Auth
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }

        return self::$instance;
    }

    /**
     * 设置模型
     */
    public function setUser(Admin $user)
    {
        $this->_user = $user;
        return $this;
    }

    /**
     * 获取模型
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * 兼容调用模型的属性
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->_user ? $this->_user->$name : NULL;
    }

    /**
     * 根据Token初始化
     *
     * @param string $token Token
     * @return boolean
     */
    public function init($token)
    {
        if ($this->_logined) {
            return TRUE;
        }
        if ($this->_error)
            return FALSE;
        $data = Token::get($token);
        if (!$data) {
            return FALSE;
        }
        $user_id = intval($data['user_id']);
        if ($user_id > 0) {
            $user = Admin::get($user_id);
            if (!$user) {
                $this->setError('Account not exist');
                return FALSE;
            }
            $this->_user = $user;
            $this->_logined = TRUE;
            $this->_token = $token;

            //初始化成功的事件
            Hook::listen("user_init_successed", $this->_user);

            return TRUE;
        } else {
            $this->setError('You are not logged in');
            return FALSE;
        }
    }

    /**
     * 注册用户
     *
     * @param string $username 用户名
     * @param string $password 密码
     * @param string $email 邮箱
     * @param string $mobile 手机号
     * @param array $extend 扩展参数
     * @return boolean
     */
    public function register($username, $password, $mobile = '', $extend = [])
    {
        // 检测用户名或邮箱、手机号是否存在
        if (Admin::getByUsername($username)) {
            $this->setError('用户名已存在');
            return FALSE;
        }
        if ($mobile && Admin::getByMobile($mobile)) {
            $this->setError('手机号已存在');
            return FALSE;
        }

        $data = [
            'username' => $username,
            'password' => $password,
            'mobile' => $mobile,
        ];

        $params = array_merge($data, [
            'addtime' => time()
        ]);
        $params = array_merge($params, $extend);
        $params['password'] = $this->getEncryptPassword($password, $data['username']);

        //账号注册时需要开启事务,避免出现垃圾数据
        Db::startTrans();
        try {
            $user = Admin::create($params, true);
            Db::commit();

            // 此时的Model中只包含部分数据
            $this->_user = Admin::get($user->id);

            //设置Token
            /* $this->_token = Random::uuid();
             Token::set($this->_token, $user->id, $this->keeptime);*/

            //注册成功的事件
            Hook::listen("user_register_successed", $this->_user);

            return TRUE;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            Db::rollback();
            return FALSE;
        }
    }

    /**
     * 用户登录
     *
     * @param string $account 账号,用户名、邮箱、手机号
     * @param string $password 密码
     * @return boolean
     */
    public function login($account, $password)
    {
        $user = Admin::get(['username' => $account]);
        if (!$user) {
            $this->setError('账号不存在');
            return FALSE;
        }

        if ($user->expire_time < time() && $user->expire_time != 0) {
            $this->setError('账户已过期');
            return FALSE;
        }

        if ($user->is_delete != 0) {
            $this->setError('账户已删除');
            return FALSE;
        }
        if ($user->password != $this->getEncryptPassword($password, $user->username)) {
            $this->setError('密码不正确');
            return FALSE;
        }

        //直接登录会员
        $this->direct($user->id);

        return TRUE;
    }

    /**
     * 注销
     *
     * @return boolean
     */
    public function logout()
    {
        if (!$this->_logined) {
            $this->setError('你没有登录');
            return false;
        }
        //设置登录标识
        $this->_logined = FALSE;
        //删除Token
        Token::delete($this->_token);
        //注销成功的事件
        Hook::listen("user_logout_successed", $this->_user);
        return TRUE;
    }

    /**
     * 修改密码
     * @param string $newpassword 新密码
     * @param string $oldpassword 旧密码
     * @param bool $ignoreoldpassword 忽略旧密码
     * @return boolean
     */
    public function changepwd($newpassword, $oldpassword = '', $ignoreoldpassword = false)
    {
        if (!$this->_logined) {
            $this->setError('你没有登录');
            return false;
        }
        //判断旧密码是否正确
        if ($this->_user->password == $this->getEncryptPassword($oldpassword, $this->_user->username) || $ignoreoldpassword) {
            $salt = $this->_user->username;
            $newpassword = $this->getEncryptPassword($newpassword, $salt);
            $this->_user->save(['password' => $newpassword]);

            $ignoreoldpassword ?: Token::delete($this->_token);
            //修改密码成功的事件
            Hook::listen("user_changepwd_successed", $this->_user);
            return true;
        } else {
            $this->setError('密码不正确');
            return false;
        }
    }

    public function changeusername($username, $password)
    {
        if (!$this->_logined) {
            $this->setError('你没有登录');
            return false;
        }
        // 检测用户名或邮箱、手机号是否存在
        if (Admin::getByUsername($username)) {
            $this->setError('用户名已存在');
            return FALSE;
        }
        $salt = $username;
        $newpassword = $this->getEncryptPassword($password, $salt);
        $this->_user->save(['username' => $username, 'password' => $newpassword]);

        return true;
    }
    /**
     * 直接登录账号
     * @param int $user_id
     * @return boolean
     */
    public function direct($user_id)
    {
        $user = Admin::get($user_id);
        if ($user) {
            $this->_user = $user;

            $this->_token = Random::uuid();
            Token::set($this->_token, $user->id, $this->keeptime);

            $this->_logined = TRUE;

            //登录成功的事件
            Hook::listen("user_login_successed", $this->_user);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 判断是否登录
     * @return boolean
     */
    public function isLogin()
    {
        if ($this->_logined) {
            return true;
        }
        return false;
    }

    /**
     * 获取当前Token
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * 获取会员基本信息
     */
    public function getUserinfo()
    {
        $data = $this->_user->toArray();
        $allowFields = $this->getAllowFields();
        $userinfo = array_intersect_key($data, array_flip($allowFields));
        $userinfo = array_merge($userinfo, Token::get($this->_token));
        return $userinfo;
    }

    /**
     * 获取当前请求的URI
     * @return string
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }

    /**
     * 设置当前请求的URI
     * @param string $uri
     */
    public function setRequestUri($uri)
    {
        $this->requestUri = $uri;
    }

    /**
     * 获取允许输出的字段
     * @return array
     */
    public function getAllowFields()
    {
        return $this->allowFields;
    }

    /**
     * 设置允许输出的字段
     * @param array $fields
     */
    public function setAllowFields($fields)
    {
        $this->allowFields = $fields;
    }

    /**
     * 删除一个指定会员
     * @param int $user_id 会员ID
     * @return boolean
     */
    public function delete($user_id)
    {
        $user = Admin::get($user_id);
        if (!$user) {
            return FALSE;
        }

        // 调用事务删除账号
        $result = Db::transaction(function ($db) use ($user_id) {
            // 删除会员
            Admin::destroy($user_id);
            // 删除会员指定的所有Token
            Token::clear($user_id);
            return TRUE;
        });
        if ($result) {
            Hook::listen("user_delete_successed", $user);
        }
        return $result ? TRUE : FALSE;
    }

    /**
     * 获取密码加密后的字符串
     * @param string $password 密码
     * @param string $salt 密码盐
     * @return string
     */
    public function getEncryptPassword($password, $salt = '')
    {
        $password = md5(md5($password) . $salt);
        return $password;
    }

    /**
     * 检测当前控制器和方法是否匹配传递的数组
     *
     * @param array $arr 需要验证权限的数组
     * @return boolean
     */
    public function match($arr = [])
    {
        $request = Request::instance();
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return FALSE;
        }
        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower($request->action()), $arr) || in_array('*', $arr)) {
            return TRUE;
        }

        // 没找到匹配
        return FALSE;
    }

    /**
     * 设置会话有效时间
     * @param int $keeptime 默认为永久
     */
    public function keeptime($keeptime = 0)
    {
        $this->keeptime = $keeptime;
    }

    /**
     * 渲染用户数据
     * @param array $datalist 二维数组
     * @param mixed $fields 加载的字段列表
     * @param string $fieldkey 渲染的字段
     * @param string $renderkey 结果字段
     * @return array
     */
    public function render(&$datalist, $fields = [], $fieldkey = 'user_id', $renderkey = 'userinfo')
    {
        $fields = !$fields ? ['id', 'nickname', 'level', 'avatar'] : (is_array($fields) ? $fields : explode(',', $fields));
        $ids = [];
        foreach ($datalist as $k => $v) {
            if (!isset($v[$fieldkey]))
                continue;
            $ids[] = $v[$fieldkey];
        }
        $list = [];
        if ($ids) {
            if (!in_array('id', $fields)) {
                $fields[] = 'id';
            }
            $ids = array_unique($ids);
            $selectlist = Admin::where('id', 'in', $ids)->column($fields);
            foreach ($selectlist as $k => $v) {
                $list[$v['id']] = $v;
            }
        }
        foreach ($datalist as $k => &$v) {
            $v[$renderkey] = isset($list[$v[$fieldkey]]) ? $list[$v[$fieldkey]] : NULL;
        }
        unset($v);
        return $datalist;
    }

    /**
     * 设置错误信息
     *
     * @param $error 错误信息
     * @return Auth
     */
    public function setError($error)
    {
        $this->_error = $error;
        return $this;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->_error;
    }

}