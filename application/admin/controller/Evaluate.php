<?php

namespace app\admin\controller;

use app\admin\model\Comment;
use app\admin\model\CommentSetting;
use app\admin\model\Visiter;
use app\platform\model\Service;
use think\Db;

/**
 *
 * 管理控制器类
 */
class Evaluate extends Base
{
    protected $login;

    public function _initialize()
    {
        parent::_initialize();

        $this->login = session('Msg');
        $this->assign('title', "评价管理");
        $this->assign('part', "评价管理");
    }

    public function index()
    {
        $keyword = $this->request->param('keyword', '');
        $star = $this->request->param('star', 0);
        $group = $this->request->param('group', 0);
        if (!empty($star)) {
            $model = Comment::hasWhere('detail', ['score' => $star])->where('business_id', $this->login['business_id'])->distinct('*');
        } elseif (!empty($group)) {
            $model = Comment::where('group_id', $group)->where('business_id', $this->login['business_id']);
        } else {
            $model = Comment::with('detail,service,group')->where('business_id', $this->login['business_id']);
        }
        if (!empty($keyword)) {
            $services = Service::where('business_id', $this->login['business_id'])
                ->where(function ($query) use ($keyword) {
                    $query->where('nick_name|user_name', 'like', "%" . $keyword . "%");
                })->select();
            $servicelist = array_column(collection($services)->toArray(), 'service_id');
            if (!empty($servicelist)) {
                $model->where('service_id', 'in', $servicelist);
                $comments = $model->order('add_time desc')->paginate();
                $this->assign('page', $comments->render());
            } else {
                $comments = [];
                $this->assign('page', null);
            }
        } else {
            $comments = $model->order('add_time desc')->paginate();
            $this->assign('page', $comments->render());
        }
        foreach ($comments as &$v) {
            $v['visiterinfo'] = Visiter::get(['visiter_id' => $v['visiter_id'], 'business_id' => $v['business_id']]);
        }
        unset($v);
        $groups = Db::name('group')->where('business_id', $this->login['business_id'])->select();
        $this->assign('groups', $groups);
        $this->assign('comments', $comments);
        $this->assign('keyword', $keyword);
        $this->assign('part', '评价管理');
        $this->assign('title', '评价管理');
        $this->assign('star', $star);
        $this->assign('group', $group);
        return $this->fetch();
    }


    public function setting()
    {
        $this->assign('part', '设置');
        $this->assign('title', '评价设置');
        return $this->fetch();
    }

    public function getSetting()
    {
        $where = [
            'business_id' => $this->login['business_id'],
        ];
        $setting = CommentSetting::get($where);
        if (!empty($setting)) {
            $setting['comments'] = json_decode($setting['comments'], true);
        }
        return json([
            'code' => 0,
            'data' => $setting,
            'msg' => 'success'
        ]);
    }

    public function saveSetting()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $comments = $this->request->post('comments/a', []);
            $data['comments'] = json_encode($comments);
            $data['business_id'] = $this->login['business_id'];
            if (empty($data['title'])) {
                return json(['code' => 1, 'msg' => '评价说明不能为空']);
            }
            if (empty($comments)) {
                return json(['code' => 1, 'msg' => '评价条目不能为空']);
            }
            foreach ($comments as $v) {
                if (mb_strlen($v) > 8 || empty($v)) {
                    return json(['code' => 1, 'msg' => '评价条目限8字且不能为空']);
                }
            }
            if ($data['word_switch'] == 'open') {
                if (empty($data['word_title'])) {
                    return json(['code' => 1, 'msg' => '文字评价名称不能为空']);
                }
            }

            $setting = CommentSetting::get(['business_id' => $data['business_id']]);
            if (!empty($setting)) {
                $res = $setting->save($data);
            } else {
                $res = CommentSetting::create($data);
            }
            if ($res !== false) {
                return json(['code' => 0, 'msg' => '操作成功']);
            } else {
                return json(['code' => 1, 'msg' => '操作失败']);
            }
        }
    }
}