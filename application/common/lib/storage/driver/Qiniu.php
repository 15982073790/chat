<?php
/**
 * @copyright ©2020 来客PHP在线客服系统
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/6/17
 * Time: 15:51
 */
namespace app\common\lib\storage\driver;

use app\common\lib\storage\Driver;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Qiniu extends Driver
{
    public function __construct($options = [])
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        parent::__construct();
    }

    public function put()
    {
        $uploadManager = new UploadManager();
        $auth = new Auth($this->options['access_key'], $this->options['secret_key']);
        $token = $auth->uploadToken($this->options['bucket']);

        $key = trim($this->saveFileFolder . '/' . $this->saveFileName, '/');
        list($result, $error) = $uploadManager->putFile(
            $token,
            $key,
            $this->file->getInfo('tmp_name')
        );
        $this->url = 'http://' . $this->options['domain'] . '/' . $result['key'];
        $this->thumbUrl = $this->url;

        return $this->save();
    }
}