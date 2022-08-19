<?php


namespace app\index\controller;

use think\Controller;
use app\index\model\User;

/**
 *
 * 留言控制器.
 * Class Message
 * @package app\index\controller
 */
class Message extends Controller
{
    /**
     * 留言首页.
     *
     * @return mixed
     */
    private function index()
    {
        return $this->fetch();
    }

    /**
     * 保存留言信息.
     *
     * @return array|string|true
     */
    private function keep()
    {
        $post = $this->request->post();

        $content = $post['content'];
        $new_content = str_replace("<", "&lt;", $content);

        $post['content'] = $new_content;
        //验证
        $result = $this->validate($post, 'Message');

        if ($result === true) {
            $res = model('message')->insert($post);
            if ($res) {
                return "提交成功";
            } else {
                return "提交失败";
            }

        } else {

            return $result;
        }

    }
}