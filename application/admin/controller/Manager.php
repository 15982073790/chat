<?php


namespace app\admin\controller;

use app\admin\model\Admins;
use app\admin\model\WechatService;
use app\platform\model\Business;
use app\platform\model\Service;
use think\File;
use think\Paginator;
use app\common\lib\Storage;
use app\common\lib\storage\StorageException;

/**
 *
 * 管理控制器类
 */
class Manager extends Base
{

   public function replyinfo()
   {

       $post = $this->request->post();

       $login = session('Msg');

       $res = model('reply')->where('service_id', $login['service_id'])->order('sort desc,id desc')->limit(10)->select();

       $data = ['code' => 0, 'data' => $res];

       return $data;

   }


    public function delreply()
    {
        $post = $this->request->post();
        $id = $post['id'];
        $res = model('reply')->where('id', $id)->delete();

        if ($res) {
            $arr = ['code' => 0, 'msg' => '删除成功!', 'data' => ''];

            return $arr;
        }
    }


    /**
     *
     * [addword description]
     * @return [type] [description]
     */
    public function addword()
    {
        $post = $this->request->post();
        $post['service_id'] = session('Msg.service_id');
        $post['word'] = $this->request->post('word', '', '\app\Common::clearXSS');
        if ($post['tag'] == '') {
            $arr = ['code' => 1, 'msg' => '标签不能为空', 'data' => ""];
            return $arr;
        }
        if ($post['word'] == '') {
            $arr = ['code' => 1, 'msg' => '快捷用语不能为空', 'data' => ""];
            return $arr;
        }
        if ($post['id'] > 0) {
//说明是编辑
            $res = model('reply')->update($post);
            if ($res) {
                $data = model('reply')->where('id', $post['id'])->find();
                $arr = ['code' => 0, 'msg' => '更新成功', 'data' => $data];
                return $arr;
            } else {
                $arr = ['code' => 1, 'msg' => '更新失败', 'data' => ""];
                return $arr;
            }
        } else {
            unset($post['id']);
            $res = model('reply')->insertGetId($post);
            if ($res) {
                $data = model('reply')->where('id', $res)->find();
                $arr = ['code' => 0, 'msg' => '添加成功', 'data' => $data];
                return $arr;
            } else {
                $arr = ['code' => 1, 'msg' => '添加失败', 'data' => ""];
                return $arr;
            }

        }


    }


    /**
     * 客服列表页面.
     *
     * @return mixed
     */
    public function info()
    {
        $login = session('Msg');

        if ($login['level'] == 'service') {
            $this->redirect('admin/index/index');
        }

        $groupdata = model('group')->where('business_id', $login['business_id'])->select();

        $groupjson = json_encode($groupdata);

        $data = model('service')->where('business_id', $login['business_id'])->paginate(8);

        $count = Service::where('parent_id', $login['service_id'])->count();

        foreach ($data as $v) {
            $clo_data = json_encode($v);

            if ($v['groupid']) {
                $res = model('group')->where('id', $v['groupid'])->find();
                $v['groupname'] = $res['groupname'];
                $v['json'] = $clo_data;
            } else {
                $v['groupname'] = '通用客服';
                $v['json'] = $clo_data;
            }
            $v['personal'] = BASEROOT . '/index/index/home?visiter_id=&visiter_name=&avatar=&business_id=' . $v['business_id'] . '&groupid=' . $v['groupid'] . '&special=' . $v['service_id'];
            $v['personalwechat'] = BASEROOT . '/index/index/wechat/business_id/' . $v['business_id'] . '/groupid/' . $v['groupid'] . '/special/' . $v['service_id'];
        }

        reset($data);
        $page = $data->render();
        $this->assign('business', session('Msg.business'));
        $this->assign('count', $count + 1);
        $this->assign('page', $page);
        $this->assign('lister', $data);
        $this->assign('group', $groupjson);
        $this->assign('title', "客服列表");
        $this->assign('part', '设置');
        return $this->fetch();
    }


    /**
     * 新增客服页面.
     *
     * @return mixed
     */
    public function add()
    {

        $login = session('Msg');
        if ($login['level'] == 'service') {
            $this->redirect('admin/index/index');
        }

        $class = model('group')->where("business_id", session('Msg.business_id'))->select();
        $this->assign('class', $class);
        return $this->fetch();
    }

    /**
     * 分组管理
     * [group description]
     * @return [type] [description]
     */
    public function group()
    {

        $login = session('Msg');

        if ($login['level'] == 'service') {
            $this->redirect('admin/index/index');
        }
        $group = model('group')->where('business_id', $login['business_id'])->order('sort desc')->paginate(8);
        $page = $group->render();

        $this->assign('part', '设置');
        $this->assign('title', '客服分组设置');
        $this->assign('group', $group);
        $this->assign('page', $page);

        return $this->fetch();
    }

    /**
     * [saveVisiter description]
     * @return [type] [description]
     */
    public function saveVisiter()
    {
        $post = $this->request->post();

        $res = model('visiter')->where('visiter_id', $post['visiter_id'])->update(['name' => $post['name'], 'tel' => $post['tel'], 'comment' => $post['comment']]);

        $arr = ['code' => 0, 'msg' => '保存成功'];
        return $arr;
    }


    /**
     * 上传头像.
     *
     * @return mixed
     */
    public function uploadpic()
    {
        try {
            Storage::$variable = 'img_head';
            $url = Storage::put();
            $data = [
                "code" => 0,
                "msg" => "",
                "data" => $url['url']
            ];
        } catch (StorageException $exception) {
            $data = ['code' => 1, 'msg' => $exception->getMessage(), 'data' => ''];
        } catch (\Exception $e) {
            $data = ['code' => 1, 'msg' => '请检查存储介质配置信息', 'data' => $e];
        }
        return $data;


        /* $file = $this->request->file("img_head");
         if ($file) {
             $newpath = ROOT_PATH . "/public/upload/images/{session('Msg.business_id')}/";
             $info = $file->validate(['ext' => 'jpg,png,gif,jpeg'])->move($newpath, time());

             if ($info) {
                 $imgname = $info->getFilename();

                 $imgpath = $this->base_root."/upload/images/{session('Msg.business_id')}/" . $imgname;

                 $data =['code'=>0,'msg'=>'','data'=>$imgpath];

                 return $data;
             } else {

                 $error =$info->getError();
                 $data =['code'=>1,'msg'=>$error,'data'=>$imgpath];
                 return $data;
             }
         }
         $data =['code'=>0,'msg'=>'','data'=>$this->base_root."/assets/images/admin/timg.jpg"];
         return $data;*/
    }


    /**
     * 更改提示音
     * [uploadvoice description]
     * @return [type] [description]
     */
    public function uploadvoice()
    {
        $login = session('Msg');
        $file = $this->request->file("voice");
        if ($file) {
            $newpath = ROOT_PATH . "/public/upload/voice/{session('Msg.business_id')}/";
            $info = $file->validate(['ext' => 'mp3,ogg,wav'])->move($newpath, $login['business_id'] . time());
            if ($info) {
                $imgname = $info->getFilename();
                $imgpath = $this->base_root . "/upload/voice/{session('Msg.business_id')}/" . $imgname;
                $res = model('business')->where('id', $login['business_id'])->update(['voice_address' => $imgpath]);

                if ($res) {
                    $data = [
                        'code' => 0,
                        'msg' => '更改成功！',
                        'data' => $imgpath
                    ];
                } else {
                    $data = [
                        'code' => 0,
                        'msg' => '没有更改！'
                    ];
                }
                return $data;
            } else {
                $error = $file->getError();
                $data = [
                    'code' => -1,
                    'msg' => $error
                ];
                return $data;
            }
        }
    }


    /**
     * 客服注册验证.
     *
     * @return [type] [description]
     */
    public function registForService()
    {

        // 获取 注册信息 数据
        $post = $this->request->post();
        // 验证 表单信息
        $result = $this->validate($post, 'Services');
        if ($result !== true) {

            $data = ['code' => 1, 'msg' => $result];

            return $data;
        }

        $login = session('Msg');

        if ($post['nick_name'] == "") {
            $post['nick_name'] = "客服" . $post['user_name'];
        }


        $num = model('service')->where('business_id', session('Msg.business_id'))->count();

        $max = Business::where('id', session('Msg.business_id'))->value('max_count');

        $maxnum = $max;

        if ($maxnum != 0 && $num >= $maxnum) {

            $data = ['code' => 2, 'msg' => '新增客服已经达到限制,不能再添加!'];
            return $data;
        }

        $service = model('service')
            ->where('user_name', $post['user_name'])
//            ->where('business_id',session('Msg.business_id'))
            ->find();

        if ($service) {
            $data = ['code' => 3, 'msg' => '该客服名已经存在！'];
            return $data;
        }

        unset($post['password2']);
        // 子添加 数据
        $post['parent_id'] = session('Msg.service_id');

        // 添加字段
        $post["business_id"] = session('Msg.business_id');

        $pass = md5($post['user_name'] . "hjkj" . $post["password"]);
        $post['password'] = $pass;
        // 保存 数据
        $debug = model('service')->insert($post);

        if ($debug) {
            $data = ['code' => 0, 'msg' => '注册成功'];
            return $data;
        }
    }

    /**
     * 添加客服分类
     * [addclass description]
     * @return [type] [description]
     */
    public function addclass()
    {

        $post = $this->request->post();

        $post['business_id'] = session('Msg.business_id');
        $post['sort'] = $post['sort'] + 0;

        $data = model('group')->insert($post);

        $sdata = ['code' => 0, 'msg' => '添加成功', 'data' => $data];

        return $sdata;
    }

    /**
     * 编辑客服分类
     * [addclass description]
     * @return [type] [description]
     */
    public function editgroup()
    {

        $post = $this->request->post();
        $post['id'] = $post['id'] + 0;
        if (!$post['id']) {
            return ['code' => 1, 'msg' => '参数非法'];
        }
        $post['business_id'] = session('Msg.business_id');
        $post['sort'] = $post['sort'] + 0;

        $data = model('group')->update($post);

        $sdata = ['code' => 0, 'msg' => '编辑成功', 'data' => $data];

        return $sdata;
    }

    /**
     * 删除客服分类
     * [delclass description]
     * @return [type] [description]
     */
    public function delclass()
    {
        $post = $this->request->post();
        $id = $post['cid'];

        $res = model('group')->where('id', $id)->delete();

        $sdata = ['code' => 0, 'msg' => '删除成功', 'data' => $res];

        return $sdata;
    }

    /**
     * 删除.
     *
     * @return string
     */
    public function delete()
    {
        $post = $this->request->post();

        if ($post['id'] == 1) {
            return ['code' => 1, 'msg' => '管理员账号无法删除'];
        }

        $data = model("service")->where('service_id', $post['id'])->delete();

        $sdata = ['code' => 0, 'msg' => '删除成功', 'data' => $data];
        return $sdata;

    }

    /**
     * 删除留言.
     *
     * @return mixed
     */
    public function deleteForMsg()
    {
        $post = $this->request->post();

        $data = model('message')->where("mid", "in", $post['chk_value'])->delete();

        $sdata = ['code' => 0, 'msg' => '删除成功', 'data' => $data];
        return $sdata;

    }

    /**
     * 个人资料更新.
     *
     * @return [type] [description]
     */
    public function update()
    {
        $post = $this->request->post();

        $file = $this->request->file('img_head');
        $login = session('Msg');

        $admin = model("service")->where(['nick_name' => $post['nickname'], 'business_id' => $login['business_id']])->find();

        if ($admin && $admin['service_id'] != $post['id']) {

            $data = ['code' => 1, 'msg' => '该昵称已经存在'];
            return $data;
        }

        $nick = $post['nickname'];
        if (mb_strlen($nick, 'UTF8') > 20) {
            $data = ['code' => 1, 'msg' => '昵称不能大于20个字符！'];
            return $data;
        }

        if ($file == "") {
            $result = model('service')->where('service_id', $post['id'])->update(['nick_name' => $post['nickname'], 'phone' => $post['phone'], 'email' => $post['email'], 'groupid' => $post['groupid'], 'open_id' => $post['open_id']]);

        } else {

            $newpath = ROOT_PATH . "/public/upload/images/" . session('Msg.business_id') . "/";
            // 可以添加 验证 规则
            $info = $file->validate(['ext' => 'jpg,png,gif,jpeg'])->move($newpath, time());

            if ($info == false) {
                $data = ['code' => 1, 'msg' => '不支持上传该图片'];
                return $data;
            }


            $imgname = $info->getFilename();
            $post['img_head'] = $this->base_root . "/upload/images/" . session('Msg.business_id') . "/" . $imgname;
            $result = model('service')->where('service_id', $post['id'])->update(['nick_name' => $post['nickname'], 'phone' => $post['phone'], 'email' => $post['email'], 'avatar' => $post['img_head'], 'groupid' => $post['groupid'], 'open_id' => $post['open_id']]);

        }

        if ($result == 0) {
            $data = ['code' => 1, 'msg' => '未被修改'];
            return $data;
        } else {
            if ($post['id'] == $login['service_id']) {
                $newdata = model('service')->where('service_id', $post['id'])->find();
                session('Msg', $newdata->getData());
                $business = Business::get(session('Msg.business_id'));
                session('Msg.business', $business->getData());
            }
            $data = ['code' => 0, 'msg' => '修改成功'];
            return $data;
        }
    }

    /**
     * 修改密码
     * [modify description]
     * @return [type] [description]
     */
    public function modify()
    {
        $post = $this->request->post();
        $result = $this->validate($post, 'Check');
        if ($result !== true) {
            return ['code' => 1, 'msg' => $result];
        }
        $user = model('service')->where("service_id", $post['id'])->find();
        $pass = md5($user['user_name'] . "hjkj" . $post['oldpass']);
        if ($user['password'] == $pass) {
            $newpass = md5($user['user_name'] . "hjkj" . $post['newpass']);
            $res = model("service")->where("service_id", $post['id'])->update(["password" => $newpass]);
            if ($res) {

                $data = ['code' => 0, 'msg' => '修改成功'];

                return $data;
            }
        } else {
            $data = ['code' => 1, 'msg' => '旧密码不正确！'];

            return $data;
        }

    }

    public function changePwd()
    {
        $post = $this->request->post();
        $result = $this->validate($post, 'Check.change_service_pwd');
        if ($result !== true) {
            return ['code' => 1, 'msg' => $result];
        }
        $user = model('service')->where("service_id", $post['id'])->find();
        $pass = md5($user['user_name'] . "hjkj" . $post['newpass']);
        $res = model("service")->where("service_id", $post['id'])->update(["password" => $pass]);
        if ($res !== false) {
            $newpass = md5($user['user_name'] . "hjkj" . $post['newpass']);

            $data = ['code' => 0, 'msg' => '修改成功'];

            return $data;
        } else {
            $data = ['code' => 1, 'msg' => '修改失败！'];

            return $data;
        }
    }
    /**
     * 查看历史记录页面.
     *
     * @return mixed
     */
    public function view()
    {

        $login = session('Msg');
        if ($login['level'] == 'service') {
            $this->redirect('admin/index/index');
        }
        $services = model("service")->where('parent_id', $login['service_id'])->select();
        $this->assign("services", $services);
        $this->assign("title", "历史记录");
        $this->assign("part", "历史记录");

        return $this->fetch();
    }

    /**
     * 添加常用语.
     *
     * @return string
     */
    public function cmtalk()
    {

        $post = $this->request->post();
        $content = $post['content'];
        $new_content = str_replace("<", "&lt;", $content);
        $post['content'] = $new_content;
        $login = session('Msg');
        $post['service_id'] = $login['service_id'];

        $result = model('sentence')->insert($post);

        if ($result) {

            $data = ['code' => 0, 'msg' => '添加成功'];
            return $data;
        }
    }

    /**
     *
     * [addquestion description]
     * @return [type] [description]
     */
    public function addquestion()
    {
        $post = $this->request->post();
        $post['business_id'] = session('Msg.business_id');
        $post['answer'] = $this->request->post('answer', '', '\app\Common::clearXSS');
        if (mb_strlen($post['keyword'], 'UTF8') > 8) {
            $data = ['code' => 1, 'msg' => '关键词不能大于8个字！'];
            return $data;
        }
        $sort = $this->request->post('sort/d', 0);
        if (!is_int($sort)) {
            $data = ['code' => 1, 'msg' => '排序字段必须是整数'];
            return $data;
        }
        $status = $this->request->post('status/d', 0);
        if (!is_int($status)) {
            $data = ['code' => 1, 'msg' => '是否显示字段非法'];
            return $data;
        }
        if (isset($post['qid']) && $post['qid'] > 0) {
            $res = model('question')->where('qid', $post['qid'])->update(['question' => $post['question'], 'answer' => $post['answer'], 'keyword' => $post['keyword'], 'sort' => $sort, 'status' => $status]);
            $arr = ['code' => 0, 'msg' => '编辑成功'];
            return $arr;
        } else {
            $res = model('question')->insert($post);
            if ($res) {
                $arr = ['code' => 0, 'msg' => '添加成功'];
                return $arr;
            }
        }
    }

    public function felete()
    {

        $post = $this->request->post();

        $id = $post['qid'];

        $result = model('question')->where('qid', $id)->delete();

        if ($result) {

        }
    }

    public function wechat()
    {
        $openId = $this->request->param('open_id');
        try {
            $user = WechatService::getUserinfo($openId);
            if (isset($user['errmsg']) && trim($user['errmsg']) != '') {
                exit($user['errmsg']);
            } else {
                $this->assign('user', $user);
            }

        } catch (Exception $exception) {
            exit($exception->getMessage());
        }
        return $this->fetch();
    }
}