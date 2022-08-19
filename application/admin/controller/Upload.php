<?php


namespace app\admin\controller;

use app\common\lib\Storage;
use app\common\lib\storage\StorageException;
use app\admin\model\Admins;
use app\admin\model\WechatPlatform;
use app\admin\model\WechatService;
use think\Db;
use think\Paginator;
use app\Common;
/**
 *
 * 后台页面控制器.
 */
class Upload extends Base
{

    public function ueditor()
    {
        !defined('UEDITORPATH') && define('UEDITORPATH', 'UEDITORPATH/');
        require './assets/ueditor/php/controller.php';
    }
}
