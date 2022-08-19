<?php
namespace app\mapp\controller;

use app\admin\model\Vgroup;
use think\Controller;
use app\mobile\model\User;
use app\Common;
use think\Paginator;


class Admin extends Base
{
    /**
     * .主页
     * [index description]
     * @return [type] [description]
     */
	public function index()
    {

        $login = session('Msg');
        /*if($login['level'] == 'service'){
            $this->redirect('admin/index/index');
        }*/
        $res = model("business")->where('id', $login['business_id'])->find();
        $groups = model('group')->where('business_id', $login['business_id'])->select();
        $group_id = 0;
        $group_name = '通用客服';
        foreach ($groups as $group_info) {
            if ($group_info['id'] === $login['groupid']) {
                $group_id = $login['groupid'];
                $group_name = $group_info['groupname'];
                break;
            }
        }

        $this->assign('voice_address', $res['voice_address']);
        $this->assign('voice', $res['voice_state']);
        $this->assign('method', $res['distribution_rule']);
        $this->assign('group_id', $group_id);
        $this->assign('group_name', $group_name);
        $this->assign('mine', $login);

        return $this->fetch();
    }

    public function group()
    {

        $login = session('Msg');
        /*if($login['level'] == 'service'){
            $this->redirect('admin/index/index');
        }*/
        $res = model("business")->where('id', $login['business_id'])->find();
        $groups = model('group')->where('business_id', $login['business_id'])->select();
        $group_id = 0;
        $group_name = '通用客服';
        foreach ($groups as $group_info) {
            if ($group_info['id'] === $login['groupid']) {
                $group_id = $login['groupid'];
                $group_name = $group_info['groupname'];
                break;
            }
        }

        $this->assign('voice_address', $res['voice_address']);
        $this->assign('voice', $res['voice_state']);
        $this->assign('method', $res['distribution_rule']);
        $this->assign('group_id', $group_id);
        $this->assign('group_name', $group_name);
        $this->assign('mine', $login);

        return $this->fetch('index');
    }

    public function book()
    {

        $login = session('Msg');
        /*if($login['level'] == 'service'){
            $this->redirect('admin/index/index');
        }*/
        $res = model("business")->where('id', $login['business_id'])->find();
        $groups = model('group')->where('business_id', $login['business_id'])->select();
        $group_id = 0;
        $group_name = '通用客服';
        foreach ($groups as $group_info) {
            if ($group_info['id'] === $login['groupid']) {
                $group_id = $login['groupid'];
                $group_name = $group_info['groupname'];
                break;
            }
        }

        $this->assign('voice_address', $res['voice_address']);
        $this->assign('voice', $res['voice_state']);
        $this->assign('method', $res['distribution_rule']);
        $this->assign('group_id', $group_id);
        $this->assign('group_name', $group_name);
        $this->assign('mine', $login);

        return $this->fetch('index');
    }


    public function setting()
    {

        $login = session('Msg');
        /*if($login['level'] == 'service'){
            $this->redirect('admin/index/index');
        }*/
        $res = model("business")->where('id', $login['business_id'])->find();
        $groups = model('group')->where('business_id', $login['business_id'])->select();
        $group_id = 0;
        $group_name = '通用客服';
        foreach ($groups as $group_info) {
            if ($group_info['id'] === $login['groupid']) {
                $group_id = $login['groupid'];
                $group_name = $group_info['groupname'];
                break;
            }
        }

        $this->assign('voice_address', $res['voice_address']);
        $this->assign('voice', $res['voice_state']);
        $this->assign('method', $res['distribution_rule']);
        $this->assign('group_id', $group_id);
        $this->assign('group_name', $group_name);
        $this->assign('mine', $login);

        return $this->fetch('index');
    }

    public function my()
    {

        $login = session('Msg');
        /*if($login['level'] == 'service'){
            $this->redirect('admin/index/index');
        }*/
        $res = model("business")->where('id', $login['business_id'])->find();
        $groups = model('group')->where('business_id', $login['business_id'])->select();
        $group_id = 0;
        $group_name = '通用客服';
        foreach ($groups as $group_info) {
            if ($group_info['id'] === $login['groupid']) {
                $group_id = $login['groupid'];
                $group_name = $group_info['groupname'];
                break;
            }
        }

        $this->assign('voice_address', $res['voice_address']);
        $this->assign('voice', $res['voice_state']);
        $this->assign('method', $res['distribution_rule']);
        $this->assign('group_id', $group_id);
        $this->assign('group_name', $group_name);
        $this->assign('mine', $login);

        return $this->fetch('index');
    }

    /**
     * .对话页面
     * [chat description]
     * @return [type] [description]
     */
    public function chat()
    {

        return $this->fetch();
    }

    /**
     *
     * [talk description]
     * @return [type] [description]
     */
    public function talk()
    {

        $login = session('Msg');
        $get = $this->request->param();
        $channel = htmlspecialchars($get['channel']);
        $avatar = htmlspecialchars($get['avatar']);
        $data = model('visiter')->where("channel", $channel)->find();

        $business = model('business')->where('id', $login['business_id'])->find();

        $this->assign("atype", $business['audio_state']);
        $this->assign("data", $data);
        $this->assign("avatar", $avatar);
        $this->assign('se', $login);
        $this->assign("img", $login['avatar']);
        return $this->fetch();
    }

    /**
     * .留言页面
     * [message description]
     * @return [type] [description]
     */
    public function message()
    {

        $login = session('Msg');
        $get = $this->request->get();
        $userAdmin = model('message');
        $pageParam = ['query' => []];
        unset($get['page']);
        if ($get) {
            $pushtime = $get['pushtime'];

            if ($pushtime) {
                if ($pushtime == 1) {
                    $timetoday = date("Y-m-d", time());
                    $userAdmin->where('timestamp', 'like', $timetoday . "%");
                    $this->assign('pushtime', $pushtime);
                    $pageParam['query']['timestamp'] = $pushtime;
                } elseif ($pushtime == 7) {
                    $timechou = strtotime("-1 week");
                    $times = date("Y-m-d", $timechou);
                    $userAdmin->where('timestamp', ">", $times);
                    $this->assign('pushtime', $pushtime);
                    $pageParam['query']['timestamp'] = $pushtime;
                }
            }
        }

        $data = $userAdmin->where('business_id', $login['business_id'])->paginate(3, false, $pageParam);
        if ($data->count() == 0) {

            $this->assign('content', '暂时没有留言数据');
        } else {
            $this->assign('content', "");
        }
        $page = $data->render();

        $this->assign('page', $page);
        $this->assign('msgdata', $data);
        return $this->fetch();
    }

    /**
     *
     * [show description]
     * @return [type] [description]
     */
    public function show()
    {

        $post = $this->request->post();

        $res = model('message')->where('mid', $post['mid'])->find();

        $data = ['code' => 0, 'data' => $res];

        return $data;

    }

    public function user()
    {
        $id = $this->request->param('id');
        $group = Vgroup::get($id);
        $this->assign('group', $group);
        $this->assign('id', $id);
        return $this->fetch();
    }

}