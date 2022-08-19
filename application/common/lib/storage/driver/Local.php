<?php
/**
 * @copyright ©2020 来客PHP在线客服系统
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/6/17
 * Time: 15:37
 */
namespace app\common\lib\storage\driver;

use app\common\lib\storage\Driver;
use app\common\lib\storage\StorageException;

class Local extends Driver
{
    protected $base_root = null;

    public function __construct()
    {
        $this->base_root = BASEROOT;
        parent::__construct();
    }

    public function put()
    {
        $info = $this->file->move(ROOT_PATH . "/public" . $this->saveFileFolder, uniqid() . time());
        if ($info) {
            $imgname = $info->getSaveName();
            $imgpath = $this->saveFileFolder . "/" . $imgname;
            $this->url = $imgpath;
            $this->thumbUrl = $this->url;
        } else {
            throw new StorageException('上传失败');
        }
        return $this->save();
    }
}