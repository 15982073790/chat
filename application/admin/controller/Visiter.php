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
class Visiter extends Base
{
    public function chat2top()
    {
        $istop = $this->request->param('istop', '');
        $visiter_id = $this->request->param('visiter_id', '');
        if ($istop === '' || $visiter_id === '') {
            $this->error('参数非法');
        }
        $res = Db::name('visiter')->where('visiter_id', $visiter_id)->update(['istop' => $istop]);
        if ($res) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }

    }
}