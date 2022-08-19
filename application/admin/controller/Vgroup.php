<?php
/**
 * Date: 2021/2/17 0017
 * 技术支持微信: zrwx978
 */
namespace app\admin\controller;

use app\admin\model\Admins;
use app\admin\model\WechatPlatform;
use app\admin\model\WechatService;
use think\Db;
use think\Paginator;
use app\Common;
/**
 *
 * 访客控制器
 */
class Vgroup extends Base
{
    protected $login;
    protected $loginModel;

    public function _initialize()
    {
        parent::_initialize();
        $login = session('Msg');
        $this->login = $login;
        $this->loginModel = Db::name('vgroup');
        $this->assign('title', "客户组管理");
        $this->assign('part', "客户组管理");
    }

    public function user_group_list()
    {
        $vid = $this->request->param('vid', '');
        if (!$vid) {
            $this->error('参数请求非法！');
        }
        $vgInfo = Db::name('visiter_vgroup')->field('GROUP_CONCAT(group_id) as group_id')->where(['vid' => $vid])->group('vid')
            ->find();
        $user_groups = [];
        if ($vgInfo && $vgInfo['group_id'] != '') {
            $user_groups = explode(',', $vgInfo['group_id']);
        }
        $group = \app\admin\model\Vgroup::where('status', 1)
            ->where('business_id', $this->login['business_id'])
            ->where('service_id', $this->login['service_id'])->select();
        $this->assign('group', $group);
        $this->assign('user_groups', $user_groups);
        return $this->fetch();
    }
}