<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2019/4/10
 * Time: 10:44
 */

namespace app\admin\controller;

use app\admin\model\Queue;
use app\admin\model\Vgroup;
use app\admin\model\Visiter;
use app\admin\model\VisiterGroup;
use EasyWeChat\User\Group;
use think\Db;

class Custom extends Base
{
    protected $login;

    public function _initialize()
    {
        parent::_initialize();
        $this->login = session('Msg');
        $this->assign('title', "客户管理");
        $this->assign('part', "客户管理");
    }

    public function index()
    {
        $group = Vgroup::where('status', 1)
            ->where('business_id', $this->login['business_id'])
            ->where('service_id', $this->login['service_id'])
            ->paginate();
        foreach ($group as &$item) {
            $item['count'] = VisiterGroup::alias('vg')
                ->join('wolive_visiter v', 'v.vid = vg.vid', 'left')
                ->join('wolive_queue q', 'q.visiter_id = v.visiter_id', 'left')
                ->where('vg.business_id', $item['business_id'])
                ->where('vg.group_id', $item['id'])
                ->where('q.state', 'neq', 'in_black_list')
                ->count();
        }
        unset($item);

        $allcount = Queue::where('business_id', $this->login['business_id'])
            ->where('service_id', $this->login['service_id'])
            ->where('state', 'neq', 'in_black_list')
            ->count();

        $messageCount = Queue::where('q.business_id', $this->login['business_id'])
            ->alias('q')
            ->where('q.service_id', $this->login['service_id'])
            ->where('q.state', 'neq', 'in_black_list')
            ->where('v.name|v.tel', 'neq', '')
            ->where('q.business_id', $this->login['business_id'])
            ->where('v.business_id', $this->login['business_id'])
            ->join('wolive_visiter v', 'v.visiter_id = q.visiter_id')
            ->count();

        $blackcount = Queue::where('service_id', $this->login['service_id'])
            ->where('business_id', $this->login['business_id'])
            ->where('state', 'in_black_list')
            ->count();

        $this->assign('allcount', $allcount);
        $this->assign('message_count', $messageCount);
        $this->assign('group', $group);
        $this->assign('blackcount', $blackcount);

        return $this->fetch();
    }

    /**
     * 编辑分组
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function editGroup()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $post['business_id'] = $this->login['business_id'];
            $post['service_id'] = $this->login['service_id'];
            if (empty($post['group_name'])) {
                return json(['code' => 1, 'msg' => '分组名不能为空']);
            }
            if (mb_strlen($post['group_name'], 'UTF8') > 20) {
                $data = ['code' => 1, 'msg' => '分组名不能多于12个字符！'];
                return json($data);
            }
            if (isset($post['id'])) {
                $group = Vgroup::get($post['id']);
                if (empty($group)) {
                    return json(['code' => 1, 'msg' => '该分组不存在']);
                }
                $where = $post;
                $where['id'] = ['<>', $post['id']];
                unset($where['bgcolor']);
                $res = Vgroup::get($where);

                if ($res['group_name'] == $post['group_name']) {
                    return json(['code' => 1, 'msg' => '该组名称已存在']);
                }
                $data = $group->save($post);
                return json(['code' => 0, 'msg' => '编辑成功', 'data' => $data]);
            } else {
                $group = Vgroup::get($post);
                if ($group) {
                    return json(['code' => 1, 'msg' => '该组名称已存在']);
                }
                $data = Vgroup::create($post);
                $sdata = ['code' => 0, 'msg' => '添加成功', 'data' => $data->getData()];
                return json($sdata);
            }
        }
    }

    /**
     * 删除分组
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function delGroup()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $post['business_id'] = $this->login['business_id'];
            $post['service_id'] = $this->login['service_id'];
            $group = Vgroup::get($post['id']);
            if (empty($group)) {
                return json(['code' => 1, 'msg' => '该分组不存在']);
            }
            $res = $group->where('id', $post['id'])->delete();
            $post['group_id'] = $post['id'];
            unset($post['id']);
            VisiterGroup::where('group_id', $post['group_id'])->delete();
            if ($res !== false) {
                return json(['code' => 0, 'msg' => '删除成功']);
            } else {
                return json(['code' => 1, 'msg' => '删除失败']);
            }
        }
    }

    public function delGroups()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post('id/a');
            $post['business_id'] = $this->login['business_id'];
            $post['service_id'] = $this->login['service_id'];
            Vgroup::destroy($post);
            VisiterGroup::where('group_id', 'in', $post)
                ->where('business_id', $this->login['business_id'])
                ->where('service_id', $this->login['service_id'])
                ->delete();
            return json(['code' => 0, 'msg' => '删除成功']);

        }
    }

    /**
     * 查找客服系统分组
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function group()
    {
        $group = Vgroup::where('status', 1)
            ->where('business_id', $this->login['business_id'])
            ->where('service_id', $this->login['service_id'])
            ->paginate();
        foreach ($group as &$item) {
            $item['count'] = VisiterGroup::alias('vg')
                ->join('wolive_visiter v', 'v.vid = vg.vid', 'left')
                ->join('wolive_queue q', 'q.visiter_id = v.visiter_id', 'left')
                ->where('vg.business_id', $item['business_id'])
                ->where('vg.group_id', $item['id'])
                ->where('q.state', 'neq', 'in_black_list')
                ->count();
        }
        unset($item);

        $allcount = Queue::where('business_id', $this->login['business_id'])
            ->where('service_id', $this->login['service_id'])
            ->where('state', 'neq', 'in_black_list')
            ->count();

        $blackcount = Queue::where('service_id', $this->login['service_id'])
            ->where('business_id', $this->login['business_id'])
            ->where('state', 'in_black_list')
            ->count();

        $this->assign('allcount', $allcount);
        $this->assign('blackcount', $blackcount);

        return json(['code' => 0, 'msg' => 'success', 'data' => $group, 'allcount' => $allcount, 'blackcount' => $blackcount]);
    }

    /**
     * 查找分组下的客户
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function visiter()
    {
        $group = $this->request->get('group_id', '0');
        if (empty($group)) {
            $vids = Queue::where('q.business_id', $this->login['business_id'])
                ->alias('q')
                ->where('q.service_id', $this->login['service_id'])
                ->where('q.state', 'neq', 'in_black_list')
                ->field('v.vid')
                ->where('v.business_id', $this->login['business_id'])
                ->join('wolive_visiter v', 'v.visiter_id = q.visiter_id', 'left')
                ->order('v.timestamp', 'desc')
                ->paginate(20);
        } elseif ($group == -1) {
            $visiter = Queue::alias('q')
                ->field('v.*')
                ->join('wolive_visiter v', 'q.visiter_id = v.visiter_id', 'left')
                ->where('q.service_id', $this->login['service_id'])
                ->where('v.business_id', $this->login['business_id'])
                ->where('q.state', 'in_black_list')
                ->order('v.timestamp', 'desc')
                ->paginate(20);
            $page = $visiter->toArray();
            $data = $page['data'];
            unset($page['data']);
            if (!empty($data)) {
                foreach ($data as &$v) {
                    $url = url('mobile/admin/talk', null, true, true);
                    $v['mobile_route_url'] = $url . "?channel=" . $v['channel'] . "&avatar=" . urlencode($v['avatar']) . "&visiter_id=" . $v['visiter_id'];
                    $v['group_name_array'] = ['黑名单'];
                }
            } else {
                $data = [];
            }
            unset($v);

            return json(['code' => 0, 'msg' => 'success', 'data' => $data, 'page' => $page]);
        } elseif ($group == -2) {
            $vids = Queue::where('q.business_id', $this->login['business_id'])
                ->alias('q')
                ->where('q.service_id', $this->login['service_id'])
                ->where('q.state', 'neq', 'in_black_list')
                ->field('v.vid')
                ->where('v.business_id', $this->login['business_id'])
                ->where('v.name|v.tel', 'neq', '')
                ->join('wolive_visiter v', 'v.visiter_id = q.visiter_id')
                ->order('v.timestamp', 'desc')
                ->paginate(20);
        } else {
            $vids = VisiterGroup::alias('vg')->where('group_id', $group)
                ->join('wolive_visiter v', 'vg.vid = v.vid', 'left')
                ->join('wolive_queue q', 'q.visiter_id = v.visiter_id', 'left')
                ->where('vg.business_id', $this->login['business_id'])
                ->where('vg.service_id', $this->login['service_id'])
                ->where('q.state', 'neq', 'in_black_list')
                ->distinct(true)
                ->field('vg.vid')
                ->order('v.timestamp', 'desc')
                ->paginate(20);
        }
        $newdata = [];
        $page = $vids->toArray();
        unset($page['data']);
        $ids = $vids->getCollection()->toArray();
        if (empty($ids)) {
            $newdata = [];
        } else {
            $visiter = Visiter::alias('v')
                ->field('v.*,g.group_name')
                ->where('v.vid', 'in', array_column($ids, 'vid'))
                ->join('wolive_visiter_vgroup vg', "v.vid = vg.vid and vg.service_id = {$this->login['service_id']}", 'left')
                ->join('wolive_vgroup g', "g.id = vg.group_id and g.service_id = {$this->login['service_id']}", 'left')
                ->order('v.timestamp', 'desc')
                ->select();
            foreach ($visiter as $k => $v) {
                if (!isset($newdata[$v['vid']])) {
                    $url = url('mobile/admin/talk', null, true, true);
                    $v['mobile_route_url'] = $url . "?channel=" . $v['channel'] . "&avatar=" . urlencode($v['avatar']) . "&visiter_id=" . $v['visiter_id'];
                    $newdata[$v['vid']] = $v;
                } else {
                    $newdata[$v['vid']]['group_name'] .= "," . $v['group_name'];
                }
            }

            foreach ($newdata as &$item) {
                $item['group_name_array'] = explode(',', $item['group_name']);
            }
            unset($item);
        }

        return json(['code' => 0, 'msg' => 'success', 'data' => array_values($newdata), 'page' => $page]);
    }

    /**
     * 批量操作客户分组
     *  group_id[]:8
     *  group_id[]:7
     *  vid[]:1
     *  vid[]:2
     *  vid[]:3
     * @return \think\response\Json
     * @throws \Exception
     */
    public function visiterGroup()
    {
        if ($this->request->isPost()) {
            $vids = $this->request->post('vid/a', []);
            $gid = $this->request->post('group_id/a', []);
            if (empty($gid)) {
                VisiterGroup::where('business_id', $this->login['business_id'])
                    ->where('service_id', $this->login['service_id'])
                    ->where('vid', 'in', $vids)
                    ->delete();
                return json(['code' => 0, 'msg' => '操作成功']);
            }

            $groups = Vgroup::where('id', 'in', $gid)
                ->where('service_id', $this->login['service_id'])
                ->where('business_id', $this->login['business_id'])
                ->count();
            $visiter = Queue::alias('q')
                ->join('wolive_visiter v', 'q.visiter_id = v.visiter_id', 'left')
                ->where('v.vid', 'in', $vids)
                ->where('q.business_id', $this->login['business_id'])
                ->count();
            if ($groups != count($gid) || $visiter != count($vids)) {
                return json(['code' => 1, 'msg' => '参数错误']);
            }
            $vgmodel = new VisiterGroup();
            $vgmodel->where('business_id', $this->login['business_id'])
                ->where('service_id', $this->login['service_id'])
                ->where('vid', 'in', $vids)
                ->delete();
            $data = [];
            foreach ($vids as $v) {
                $temp['vid'] = $v;
                $temp['business_id'] = $this->login['business_id'];
                $temp['service_id'] = $this->login['service_id'];
                foreach ($gid as $g) {
                    $temp['group_id'] = $g;
                    $data[] = $temp;
                }
            }
            $res = $vgmodel->saveAll($data);
            if ($res !== false) {
                $data = ['code' => 0, 'msg' => '操作成功'];
            } else {
                $data = ['code' => 1, 'msg' => '操作失败'];
            }
            return json($data);
        }
    }

    public function mVisiterGroup()
    {
        if ($this->request->isPost()) {
            $vids = $this->request->post('vid/a', []);
            $gid = $this->request->post('group_id', 0);
            if (empty($vids) || empty($gid)) {
                return json(['code' => 1, 'msg' => '请选择分组或客户']);
            }
            if ($gid != -1) {
                $group = Vgroup::get($gid);
                if (empty($group)) {
                    return json(['code' => 1, 'msg' => '分组不存在']);
                }
                $vgmodel = new VisiterGroup();
                $visiter = $vgmodel->where('business_id', $this->login['business_id'])
                    ->where('service_id', $this->login['service_id'])
                    ->where('vid', 'in', $vids)
                    ->select();

                Db::name('visiter_vgroup')->where('vid', 'in', $vids)
                    ->where('service_id', $this->login['service_id'])
                    ->delete();
                $data = [];
                foreach ($vids as $v) {
                    $temp['vid'] = $v;
                    $temp['business_id'] = $this->login['business_id'];
                    $temp['service_id'] = $this->login['service_id'];
                    $temp['group_id'] = $gid;
                    $data[] = $temp;
                }
                foreach ($visiter as $v) {
                    $temp['vid'] = $v['vid'];
                    $temp['business_id'] = $this->login['business_id'];
                    $temp['service_id'] = $this->login['service_id'];
                    $temp['group_id'] = $v['group_id'];
                    $data[] = $temp;
                }
                $data = array_unique($data, SORT_REGULAR);
                $res = $vgmodel->saveAll($data);
                if ($res !== false) {
                    $data = ['code' => 0, 'msg' => '操作成功'];
                } else {
                    $data = ['code' => 1, 'msg' => '操作失败'];
                }
                return json($data);
            } else {
                $result = Db::name('queue')->alias('q')
                    ->join('wolive_visiter v', 'v.visiter_id = q.visiter_id', 'LEFT')
                    ->where('q.business_id', $this->login['business_id'])
                    ->where('q.service_id', $this->login['service_id'])
                    ->where('v.vid', 'in', $vids)
                    ->update(['q.state' => 'in_black_list']);
                $arr = ['code' => 0, 'msg' => '操作成功', 'data' => $result];
                return json($arr);
            }
        }
    }

    public function removeGroup()
    {
        if ($this->request->isPost()) {
            $vids = $this->request->post('vid/a', []);
            $gid = $this->request->post('group_id', 0);
            if (empty($gid)) {
                return json($data = ['code' => 1, 'msg' => '请选择分组']);
            } elseif ($gid == -1) {
                $result = Queue::alias('q')->where('q.business_id', $this->login['business_id'])
                    ->join('wolive_visiter v', 'q.visiter_id = v.visiter_id', 'left')
                    ->join('wolive_visiter_vgroup vg', "v.vid = vg.vid AND  vg.service_id = {$this->login['service_id']}", 'left')
                    ->where('v.vid', 'in', $vids)
                    ->update(['q.state' => 'normal']);
                if ($result !== false) {
                    $data = ['code' => 0, 'msg' => '操作成功'];
                } else {
                    $data = ['code' => 1, 'msg' => '操作失败'];
                }
                return json($data);
            }
            $res = VisiterGroup::where('vid', 'in', $vids)
                ->where('group_id', $gid)
                ->delete();
            if ($res !== false) {
                $data = ['code' => 0, 'msg' => '操作成功'];
            } else {
                $data = ['code' => 1, 'msg' => '操作失败'];
            }
            return json($data);
        }
    }

    /**
     * 查找某个分组下某客户
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function search()
    {
        $group = $this->request->get('group_id', '0');
        $nickname = $this->request->get('nickname', '');
        if (empty($group) || $group == -2) {
            $vids = Queue::where('q.business_id', $this->login['business_id'])
                ->alias('q')
                ->where('q.service_id', $this->login['service_id'])
                ->where('q.state', 'neq', 'in_black_list')
                ->field('v.vid')
                ->join('wolive_visiter v', 'v.visiter_id = q.visiter_id')
                ->where('v.name|v.visiter_name', 'like', '%' . $nickname . '%')
                ->where('v.business_id', $this->login['business_id'])
                ->order('v.timestamp', 'desc')
                ->paginate(20);


            $newdata['data'] = [];
            $page = $vids->toArray();
            unset($page['data']);
            $ids = $vids->getCollection()->toArray();
            if (empty($ids)) {
                $newdata['data'] = [];
            } else {
                $visiter = Visiter::alias('v')
                    ->field('v.*,g.group_name')
                    ->where('v.vid', 'in', array_column($ids, 'vid'))
                    ->join('wolive_visiter_vgroup vg', "v.vid = vg.vid and vg.service_id = {$this->login['service_id']}", 'left')
                    ->join('wolive_vgroup g', "g.id = vg.group_id and g.service_id = {$this->login['service_id']}", 'left')
                    ->order('v.timestamp', 'desc')
                    ->select();

                foreach ($visiter as $k => $v) {
                    if (!isset($newdata['data'][$v['vid']])) {
                        $url = url('mobile/admin/talk', null, true, true);
                        $v['mobile_route_url'] = $url . "?channel=" . $v['channel'] . "&avatar=" . urlencode($v['avatar']) . "&visiter_id=" . $v['visiter_id'];
                        $newdata['data'][$v['vid']] = $v;
                    } else {
                        $newdata['data'][$v['vid']]['group_name'] .= "," . $v['group_name'];
                    }
                }

                foreach ($newdata['data'] as &$item) {
                    $item['group_name_array'] = explode(',', $item['group_name']);
                }
                unset($item);
            }
            $data = array_values($newdata['data']);
            $data['data'] = $data;
            $data['current_page'] = $vids->currentPage();
            $data['per_page'] = $vids->listRows();
            $data['total'] = $vids->total();

            return json(['code' => 0, 'msg' => 'success', 'data' => $data]);
        } elseif ($group == -1) {
            $model = Queue::alias('q')
                ->join('wolive_visiter v', 'q.visiter_id = v.visiter_id', 'left')
                ->where('v.business_id', $this->login['business_id'])
                ->where('v.name|v.visiter_name', 'like', '%' . $nickname . '%');
            $res = $model->where('q.state', 'in_black_list')
                ->order('v.timestamp', 'desc')
                ->paginate(20);
            foreach ($res as &$v) {
                $v['create_time'] = time(); // 防止报错
                $v['group_name_array'] = ['黑名单'];
                $url = url('mobile/admin/talk', null, true, true);
                $v['mobile_route_url'] = $url . "?channel=" . $v['channel'] . "&avatar=" . urlencode($v['avatar']) . "&visiter_id=" . $v['visiter_id'];
            }
            unset($v);
            return json(['code' => 0, 'msg' => 'success', 'data' => $res]);
        } else {
            $model = Queue::alias('q')
                ->field('v.*,GROUP_CONCAT(g.group_name) as group_name')
                ->where('v.name|v.visiter_name', 'like', '%' . $nickname . '%')
                ->join('wolive_visiter v', 'q.visiter_id = v.visiter_id', 'left')
                ->join('wolive_visiter_vgroup vg', "v.vid = vg.vid", 'left')
                ->join('wolive_vgroup g', "g.id = vg.group_id", 'left')
                ->where('vg.service_id', "{$this->login['service_id']}")
                ->where('v.business_id', $this->login['business_id'])
                ->order('v.timestamp', 'desc')
                ->group('v.vid');
            $res = $model->where('g.id', $group)->where('q.state', 'neq', 'in_black_list')->paginate(20);
            foreach ($res as &$v) {
                $v['create_time'] = time(); // 防止报错
                $v['group_name_array'] = explode(',', $v['group_name']);
                $url = url('mobile/admin/talk', null, true, true);
                $v['mobile_route_url'] = $url . "?channel=" . $v['channel'] . "&avatar=" . urlencode($v['avatar']) . "&visiter_id=" . $v['visiter_id'];
            }
            unset($v);
            return json(['code' => 0, 'msg' => 'success', 'data' => $res]);
        }
    }

    /**
     * 批量加黑名单
     */
    public function moreblack()
    {
        if ($this->request->isPost()) {
            $vids = $this->request->post('vid/a', []);
            if (empty($vids)) {
                return json(['code' => 1, 'msg' => '参数不正确']);
            }
            $result = Db::name('queue')->where('visiter_id', 'in', $vids)->where('business_id', $this->login['business_id'])->update(['state' => 'in_black_list']);
            $arr = ['code' => 0, 'msg' => 'success', 'data' => $result];
            return $arr;
        }
    }

    /**
     * 批量加黑名单
     */
    public function bitchDelete()
    {
        if ($this->request->isPost()) {
            $vids = $this->request->post('vid/a', []);
            if (empty($vids)) {
                return json(['code' => 1, 'msg' => '参数不正确']);
            }
            Visiter::where('visiter_id', 'in', $vids)->delete();
            $result = Queue::where('visiter_id', 'in', $vids)->delete();
            //  $result = Chats::where('visiter_id','in',$vids)->delete();
            $arr = ['code' => 0, 'msg' => 'success', 'data' => $result];
            return $arr;
        }
    }

    /**
     * 重新打开访客
     * @return \think\response\Json
     */
    public function openCs()
    {
        if ($this->request->isPost()) {
            $visiter_id = $this->request->post('visiter_id');
            Queue::where('visiter_id', $visiter_id)
                ->where('business_id', $this->login['business_id'])
                ->where('service_id', $this->login['service_id'])
                ->update(['state' => 'normal']);
            return json(['code' => 0, 'msg' => 'success']);
        }
    }
}