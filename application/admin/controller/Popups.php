<?php

namespace app\admin\controller;
use app\admin\model\Admins;
use app\admin\model\WechatPlatform;
use app\admin\model\WechatService;
use think\Db;
use think\Paginator;
use app\Common;
/**
 *
 * 后台弹窗处理.
 */
class Popups extends Base
{
    //    展示快捷回复
    public function quickreply()
    {
        $id = $this->request->param('id', 0, 'intval');
        $data = [];
        if ($id) {
            $data = model('reply')->where('id', $id)->find();
        }
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function setcustom()
    {
        $post = $this->request->post();
        $post['business_id'] = session('Msg.business_id');
        $post['content'] = $this->request->post('content', '', '\app\Common::clearXSS');

        if (isset($post['sid']) && $post['sid'] > 0) {
            $res = model('sentence')->where('sid', $post['sid'])->update(['sid' => $post['sid'], 'content' => $post['content']]);
            $arr = ['code' => 0, 'msg' => '编辑成功'];
            return $arr;
        } else {
//            content	text	内容
//service_id
            $result = model('sentence')->insert(
                ['content' => $post['content'],
                    'service_id' => session('Msg.service_id')]
            );
            if ($result) {

                $data = ['code' => 0, 'msg' => '添加成功'];
                return $data;
            } else {
                $arr = ['code' => 1, 'msg' => '添加失败'];
                return $arr;
            }

        }
    }

}