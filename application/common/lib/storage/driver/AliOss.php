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
use OSS\OssClient;

class AliOss extends Driver
{
    /**
     * 架构函数
     * @param array $options
     */
    public function __construct($options = [])
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        parent::__construct();
    }

    public function put()
    {
        $isCName = (!empty($this->options['cname']) && $this->options['cname'] == 1) ? true : false;
        $client = new OssClient($this->options['access_key'], $this->options['secret_key'], $this->options['domain'], $isCName);

        $object = trim($this->saveFileFolder . '/' . $this->saveFileName, '/');
        $client->uploadFile($this->options['bucket'], $object, $this->file->getInfo('tmp_name'));
        if (!$isCName) {
            $endpointNameStart = mb_stripos($this->options['domain'], '://') + 3;
            $urlPrefix = mb_substr($this->options['domain'], 0, $endpointNameStart)
                . $this->options['bucket']
                . '.'
                . mb_substr($this->options['domain'], $endpointNameStart);
        } else {
            $urlPrefix = $this->options['domain'];
        }
        $this->url = $urlPrefix . $this->saveFileFolder . '/' . $this->saveFileName;
        $this->thumbUrl = $this->url;

        return $this->save();
    }
}