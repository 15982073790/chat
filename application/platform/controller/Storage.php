<?php
/**
 * @copyright ©2020 来客PHP在线客服系统
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/6/19
 * Time: 9:53
 */

namespace app\platform\controller;


use app\platform\model\Admin;
use app\platform\model\Option;
use app\platform\service\Menu;

class Storage extends Base
{
    protected $noNeedLogin = [];
    protected $storage = [
        'AliOss' => [
            'bucket' => '',
            'domain' => '',
            'cname' => '0',
            'access_key' => '',
            'secret_key' => ''
        ],
        'TxCos' => [
            'bucket' => '',
            'region' => '',
            'domain' => '',
            'secret_id' => '',
            'secret_key' => ''
        ],
        'Qiniu' => [
            'bucket' => '',
            'domain' => '',
            'access_key' => '',
            'secret_key' => ''
        ]
    ];

    public function index()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $type = $post['storage_type'];
            switch ($type) {
                case \app\common\lib\Storage::STORAGE_TYPE_LOCAL:
                    $storage = 'Local';
                    break;
                case \app\common\lib\Storage::STORAGE_TYPE_ALIOSS:
                    $storage = 'AliOss';
                    break;
                case \app\common\lib\Storage::STORAGE_TYPE_TXCOS:
                    $storage = 'TxCos';
                    break;
                case \app\common\lib\Storage::STORAGE_TYPE_QINIU:
                    $storage = 'Qiniu';
                    break;
                default:
                    throw new \Exception('未知的存储位置: type=' . $type);
                    break;
            }

            $config = isset($post[$storage]) ? $post[$storage] : [];
            foreach ($config as $k => $item) {
                if (!isset($item) || $item == '') {
                    return ['code' => 1, 'msg' => '请填写' . $k];
                }
            }
            $model = \app\admin\model\Storage::get(['status' => 1, 'admin_id' => $this->auth->getUser()->id]);
            if ($model) {
                $model->save(['config' => json_encode($config), 'type' => $type]);
            } else {
                \app\admin\model\Storage::create(['config' => json_encode($config), 'type' => $type, 'admin_id' => $this->auth->getUser()->id, 'status' => 1]);
            }

            Option::setList([
                [
                    'title' => 'image_size',
                    'value' => $this->request->post('image_size'),
                ],
                [
                    'title' => 'file_size',
                    'value' => $this->request->post('file_size'),
                ],
            ], 0, 'admin');

            return ['code' => 0, 'msg' => '更改成功'];
        } else {
            $uid = $this->auth->getUser()->id;
            $storage = \app\admin\model\Storage::get(['admin_id' => $uid]);
            $permission = Menu::getPermission();
            if ($uid == 1) {
                $permission['storage'] = ['Local', 'AliOss', 'TxCos', 'Qiniu'];
            }
            $this->assign('permission', isset($permission['storage']) ? $permission['storage'] : []);
            $this->assign('Local', []);
            $this->assign($this->storage);
            $type = empty($storage['type']) ? '1' : $storage['type'];
            $this->assign('type', $type);
            $config = isset($storage['config']) ? json_decode($storage['config'], true) : [];
            switch ($type) {
                case \app\common\lib\Storage::STORAGE_TYPE_LOCAL:
                    $config = 'Local';
                    break;
                case \app\common\lib\Storage::STORAGE_TYPE_ALIOSS:
                    foreach ($this->storage['AliOss'] as $key => $v) {
                        if (!isset($config[$key])) {
                            $config[$key] = $v;
                        }
                    }
                    $this->assign('AliOss', $config);
                    break;
                case \app\common\lib\Storage::STORAGE_TYPE_TXCOS:
                    $this->assign('TxCos', $config);
                    break;
                case \app\common\lib\Storage::STORAGE_TYPE_QINIU:
                    $this->assign('Qiniu', $config);
                    break;
                default:
                    throw new \Exception('未知的存储位置: type=' . $storage['type']);
                    break;
            }

            $option = Option::getList('image_size,file_size', 0, 'admin');
            $this->assign('size', $option);
            return $this->fetch();
        }
    }
}