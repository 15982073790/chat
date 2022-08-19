<?php
/**
 * @copyright ©2020 来客PHP在线客服系统
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/5/14
 * Time: 16:42
 */
namespace app\admin\controller;

use app\admin\model\RestSetting;

class Rest extends Base
{
    protected $login;

    public function _initialize()
    {
        parent::_initialize();
        $login = session('Msg');
        $this->login = $login;
        $this->assign('title', "下班设置");
        $this->assign('part', "下班设置");
    }

    public function setting()
    {
        $this->assign('part', '设置');
        $this->assign('title', '下班设置');
        return $this->fetch();
    }

    public function getSetting()
    {
        $where = [
            'business_id' => $this->login['business_id'],
        ];
        $setting = RestSetting::get($where);
        $data['dialog'] = $setting['state'] == 'open' ? true : false;
        $start_time = explode(':', $setting['start_time']);
        $data['start']['hour'] = $start_time[0];
        $data['start']['minutes'] = $start_time[1];
        $end_time = explode(':', $setting['end_time']);
        $data['off']['hour'] = $end_time[0];
        $data['off']['minutes'] = $end_time[1];
        $data['week'] = json_decode($setting['week'], true);
        $data['reply'] = $setting['reply'];
        $data['name_input'] = $setting['name_state'] == 'open' ? true : false;
        $data['tel_input'] = $setting['tel_state'] == 'open' ? true : false;
        return json([
            'code' => 0,
            'data' => $data,
            'msg' => 'success'
        ]);
    }

    public function saveSetting()
    {
        if ($this->request->isPost()) {
            $data['business_id'] = $this->login['business_id'];
            $data['state'] = $this->request->post('dialog') == 'true' ? 'open' : 'close';
            $start_time = $this->request->post('start/a', []);
            $start_time['minutes'] = !empty($start_time['minutes']) ? $start_time['minutes'] : '00';
            $data['start_time'] = $start_time['hour'] . ":" . $start_time['minutes'];
            $end_time = $this->request->post('off/a', []);
            $end_time['minutes'] = !empty($end_time['minutes']) ? $end_time['minutes'] : '00';
            $data['end_time'] = $end_time['hour'] . ":" . $end_time['minutes'];
            $week = $this->request->post('week/a', []);
            $data['week'] = json_encode($week);
            $data['reply'] = $this->request->post('reply', '');
            $data['name_state'] = $this->request->post('name_input') == 'true' ? 'open' : "close";
            $data['tel_state'] = $this->request->post('tel_input') == 'true' ? 'open' : "close";
            $setting = RestSetting::get(['business_id' => $data['business_id']]);
            if (!empty($setting)) {
                $res = $setting->save($data);
            } else {
                $res = RestSetting::create($data);
            }
            if ($res !== false) {
                return json(['code' => 0, 'msg' => '操作成功']);
            } else {
                return json(['code' => 1, 'msg' => '操作失败']);
            }
        }
    }
}