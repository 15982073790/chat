<?php

namespace app\weixin\controller;

use app\weixin\model\Admins;

class Chat extends Base
{

    public function index()
    {

        return $this->fetch();
    }

    public function talk()
    {

        $login = session('Msg');
        $get = $this->request->get();
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


    public function chatdata()
    {

        $login = session('Msg');
        $service_id = $login['service_id'];
        $post = $this->request->post();


        if ($post["hid"] == '') {

            $data = model('chats')->where(['service_id' => $service_id, 'visiter_id' => $post['visiter_id'], 'business_id' => $login['business_id']])->order('timestamp desc')->limit(10)->select();

            $vdata = model('visiter')->where('visiter_id', $post['visiter_id'])->where('business_id', $login['business_id'])->find();

            $sdata = model('service')->where('service_id', $service_id)->find();

            foreach ($data as $v) {

                if ($v['direction'] == 'to_service') {
                    $v['avatar'] = $vdata['avatar'];
                } else {

                    $v['avatar'] = $sdata['avatar'];
                }

            }

            reset($data);


        } else {


            $data = model('chats')->where(['service_id' => $service_id, 'visiter_id' => $post['visiter_id'], 'business_id' => $login['business_id']])->where('cid', '<', $post['hid'])->order('timestamp desc')->limit(10)->select();

            $vdata = model('visiter')->where('visiter_id', $post['visiter_id'])->where('business_id', $login['business_id'])->find();

            $sdata = model('service')->where('service_id', $service_id)->find();


            foreach ($data as $v) {

                if ($v['direction'] == 'to_service') {
                    $v['avatar'] = $vdata['avatar'];
                } else {
                    $v['avatar'] = $sdata['avatar'];
                }

            }

            reset($data);

        }

        $result = array_reverse($data);

        $data = ['code' => 0, 'data' => $result];
        return $data;

    }

}