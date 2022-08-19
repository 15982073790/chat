<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/1/30
 * Time: 14:54
 */
namespace app\platform\controller;



use app\platform\model\Business;
use app\platform\model\Service;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

class App extends Base
{

    protected $noNeedLogin = [];

    public function index()
    {
        $keyword = $this->request->param('keyword');
        $where = [
            'admin_id' => $this->admin['id'],
            'is_delete' => 0,
        ];
        !empty($keyword) && $where['business_name'] = ['like', "%" . trim($keyword) . "%"];
        $count = Business::where($where)->count();

        $where['is_recycle'] = 0;
        $list = Business::where($where)->paginate();
        $page = $list->render();
        $this->assign('page', $page);
        $this->assign('list', $list);
        $this->assign('keyword', $keyword);
        $this->assign('app_count', $count);
        return $this->fetch();
    }

    public function modifyPassword()
    {
        $id = $this->request->param('id');

        $business = Business::get([
            'id' => $id,
            'admin_id' => $this->admin['id']
        ]);
        if (!$business) {
            return [
                'code' => 1,
                'msg' => '数据错误，请刷新页面后重试',
            ];
        }

        $paswword = $this->request->post('password');
        if (strlen($paswword) < 6) {
            return [
                'code' => 1,
                'msg' => '密码不能少于6位',
            ];
        }

        $service = $business->service;
        $pass = md5($service['user_name'] . "hjkj" . $paswword);
        $res = $service->save(['password' => $pass]);

        if ($res !== false) {
            return [
                'code' => 0,
                'msg' => '修改密码成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '修改密码失败',
            ];
        }
    }

    public function edit()
    {
        $id = $this->request->param('id');
        if ($this->request->isPost()) {

            $post = $this->request->param();
            $post['admin_id'] = isset($post['admin_id']) ? $post['admin_id'] : $this->admin['id'];

            if (isset($post['no_expire_time'])) {
                $post['expire_time'] = 0;
                unset($post['no_expire_time']);
            } else {
                $post['expire_time'] = strtotime($post['expire_time'] . ' 23:59:59');
            }

            $validate = Loader::validate('App');
            $sence = isset($post['id']) ? 'edit' : 'insert';
            if (!$validate->scene($sence)->check($post)) {
                return ['code' => 1, 'msg' => $validate->getError()];
            }
            if (is_array(json_decode($this->admin['permission'], true))) {
                $is_copyright = in_array('copyright', json_decode($this->admin['permission'], true));
            } else {
                $is_copyright = false;
            }

            if ($this->admin['id'] == 1) {
                $is_copyright = true;
            }

            if ($is_copyright == false) {
                if ((isset($post['logo']) || isset($post['copyright']))) {
                    return ['code' => 1, 'msg' => '您无此权限'];
                }
                $post['logo'] = $this->option['logo'];
                $post['copyright'] = $this->option['copyright'];
            } else {
                $post['copyright'] = $this->request->post('copyright', '', null);
            }

            if (!isset($post['id'])) {
                $count = Business::where([
                    'admin_id' => $this->admin['id'],
                    'is_delete' => 0,
                ])->count();
                if ($count && $this->admin['app_max_count'] && $count >= $this->admin['app_max_count']) {
                    return ['code' => 1, 'msg' => '客服系统创建数量超过上限'];
                }
                $business = Business::get(['admin_id' => $post['admin_id'], 'business_name' => $post['business_name'], 'is_delete' => 0]);
                if ($business) {
                    return ['code' => 1, 'msg' => '客服系统名称已存在'];
                }
                $service = model('service')
                    ->where('user_name', $post['user_name'])
                    ->find();
                if ($service) {
                    $data = ['code' => 1, 'msg' => '新增管理员用户名已存在！'];
                    return $data;
                }
                $res = Business::addBusiness($post);
            } else {

                $business = Business::get(['admin_id' => $post['admin_id'], 'business_name' => $post['business_name'], 'id' => ['<>', $post['id'], 'is_delete' => 0]]);
                if ($business) {
                    return ['code' => 1, 'msg' => '客服系统名称已存在'];
                }
                $res = Business::editBusiness($post);
            }
            if ($res !== false) {
                return ['code' => 0, 'msg' => '操作成功'];
            } else {
                return ['code' => 1, 'msg' => '操作失败，请重试'];
            }
        } else {
            $business = null;
            if ($id) {
                if ($this->admin['id'] == 1) {
                    $business = Business::get(['id' => $id]);
                } else {
                    $business = Business::get(['id' => $id, 'admin_id' => $this->admin['id']]);
                    if (empty($business)) {
                        $this->error('您无此权限,请刷新');
                    }
                }
                $url = url('edit', ['id' => $id]);
            } else {
                $url = url('edit');
            }
            $count = Business::where([
                'admin_id' => $this->admin['id'],
                'is_delete' => 0,
            ])->count();
            $account_max = $this->admin['app_max_count'];
            $account_over_max = !isset($business) && $account_max != 0 && $count >= $account_max;

            if (is_array(json_decode($this->admin['permission'], true))) {
                $is_copyright = in_array('copyright', json_decode($this->admin['permission'], true));
            } else {
                $is_copyright = false;
            }

            if ($this->admin['id'] == 1) {
                $is_copyright = true;
            }

            $data = [
                'option' => $this->option,
                'action_url' => $url,
                'model' => $business,
                'count' => $count,
                'account_max' => $account_max,
                'account_over_max' => $account_over_max,
                'is_copyright' => $is_copyright
            ];
            $this->assign($data);
            $this->view->engine->layout(false);
            return $this->fetch();
        }
    }

    public function entry()
    {
        $id = $this->request->param('id');
        $condition = [
            'id' => $id,
            'admin_id' => $this->admin['id'],
            'is_delete' => 0,
        ];
        if ($this->admin['id'] == 1) {
            unset($condition['admin_id']);
        }
        $app = Business::get($condition);
        if (!$app) {
            $this->redirect('app/index');

        }
        $service = Service::get(['business_id' => $id, 'level' => 'super_manager']);
        session('Msg', $service->toArray());
        session('Msg.business', $app->toArray());
        session('Platform.referer', $id);
        $this->redirect(url('admin/index/index'));
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param('id');
        $condition = [
            'id' => $id,
            'admin_id' => $this->admin['id'],
            'is_delete' => 0,
        ];
        if ($this->admin['id'] == 1) {
            unset($condition['admin_id']);
        }
        $store = Business::get($condition);
        if ($store) {
            $store->is_delete = 1;
            $store->save();
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    public function recycle()
    {
        $keyword = $this->request->param('keyword');
        $where = [
            'admin_id' => $this->admin['id'],
            'is_delete' => 0,
            'is_recycle' => 1,
        ];
        !empty($keyword) && $where['business_name'] = ['like', "%" . trim($keyword) . "%"];
        $list = Business::where($where)->paginate();

        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('keyword', $keyword);
        return $this->fetch('recycle');
    }

    public function subapp()
    {
        $keyword = $this->request->param('keyword');
        $query = Business::hasWhere('admin', ['username' => ['like', "%" . $keyword . "%"]])->where('Business.is_delete', 0)->where('admin_id', '<>', $this->admin['id'])->with('service,admin');
        if ($keyword = trim($keyword)) {
            $query->whereOr(function ($query) use ($keyword) {
                $query->where('business_name', 'like', "%" . $keyword . "%")->where('Business.is_delete', 0)->where('admin_id', '<>', $this->admin['id']);
            });
        }

        $list = $query->paginate();
        $page = $list->render();
        return $this->fetch('subapp', [
            'list' => $list,
            'keyword' => $keyword,
            'page' => $page,
        ]);

        return $this->fetch();
    }

    public function setRecycle()
    {
        $action = $this->request->param('action');
        $id = $this->request->param('id');

        $action = $action == '1' ? '1' : '0';
        $condition = [
            'id' => $id,
            'admin_id' => $this->admin['id'],
        ];

        if ($this->admin['id'] == 1) {
            unset($condition['admin_id']);
        }

        $business = Business::get($condition);
        if ($business) {
            $res = $business->save(['is_recycle' => $action]);
        }

        if ($res) {
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '操作失败',
            ];
        }
    }

    public function disabled()
    {
        $action = $this->request->param('action');
        $id = $this->request->param('id');

        $action = $action == 'close' ? 'open' : 'close';
        $condition = [
            'id' => $id,
            'admin_id' => $this->admin['id'],
        ];

        if ($this->admin['id'] == 1) {
            unset($condition['admin_id']);
        }

        $business = Business::get($condition);
        if ($business) {
            $res = $business->save(['state' => $action]);
        }

        if ($res) {
            return [
                'code' => 0,
                'msg' => '操作成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '操作失败',
            ];
        }
    }

    public function truncate()
    {
        $business_id = $this->request->param('id');
        if ($this->admin['id'] != 1) {
            $where = ['id' => $business_id, 'admin_id' => $this->admin['id']];
        } else {
            $where = ['id' => $business_id];
        }
        $business = Business::get($where);
        if (empty($business)) {
            if ($this->request->isPost()) {
                return [
                    'code' => 1,
                    'msg' => '您无此权限',
                ];
            } else {
                $this->error('您无此权限');
            }
        }
        if ($this->request->isPost()) {
            $log = [];
            $path = [];
            $talk = $this->request->param('talk', '');
            $photo = $this->request->param('photo', '');
            $file = $this->request->param('file', '');
            $talk_time = $this->request->param('talk_time', 0);
            if ($photo == 'on') {
                array_push($log, 'photo');
                array_push($path, ROOT_PATH . "/public/upload/images/{$business_id}/");
            }
            if ($file == 'on') {
                array_push($log, 'file');
                array_push($path, ROOT_PATH . "/public/upload/files/{$business_id}/");
            }
            if ($talk == 'on') {
                array_push($log, 'talk');
                $map = ['business_id' => $business_id];
                switch ($talk_time) {
                    case 0:
                        array_push($log, 'talk_time_0');
                        break;

                    case 1:
                        array_push($log, 'talk_time_-1 week');
                        $map['timestamp'] = ['<', strtotime("-1 week")];
                        break;

                    case 2:
                        array_push($log, 'talk_time_-1 month');
                        $map['timestamp'] = ['<', strtotime("-1 month")];
                        break;

                    case 3:
                        array_push($log, 'talk_time_-3 month');
                        $map['timestamp'] = ['<', strtotime("-3 month")];
                        break;

                    case 4:
                        array_push($log, 'talk_time_-1 year');
                        $map['timestamp'] = ['<', strtotime("-1 year")];

                        break;
                }
                Log::info('===============CLEAN BEGIN===============');
                Log::info(json($log));
                Log::info('===============CLEAN END==============');
                Db::name('chats')->where($map)->delete();
            }

            $tool = new \app\platform\model\Cache();
            $switch = ($photo == 'on' || $file == 'on') ? true : false;
            $tool->setSystem($switch);
            $tool->setSystempath($path);
            return $tool->clear();
        } else {
            $tool = new \app\platform\model\Cache();
            try {
                $picsize = $tool->dirsize(ROOT_PATH . "/public/upload/images/{$business_id}/");
                $filesize = $tool->dirsize(ROOT_PATH . "/public/upload/files/{$business_id}/");
            } catch (Exception $e) {
                $picsize = isset($picsize) ? $picsize : 0;
                $filesize = isset($filesize) ? $filesize : 0;
            }
            $data = [
                'count' => Db::name('chats')->where('business_id', $business_id)->count(),
                'picsize' => round($picsize / 1024 / 1024, 2),
                'filesize' => round($filesize / 1024 / 1024, 2),
                'action_url' => url('truncate', ['id' => $business_id]),
            ];
            $this->assign($data);
            $this->view->engine->layout(false);
            return $this->fetch();
        }
    }
}