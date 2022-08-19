<?php
/**
 * @copyright ©2020 来客PHP在线客服系统
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/6/17
 * Time: 15:33
 */
namespace app\common\lib\storage;

use app\common\lib\Storage;
use app\platform\model\Option;

abstract class Driver
{
    protected $options = [];
    protected $url;
    protected $thumbUrl;
    protected $saveFileFolderl;
    protected $saveThumbFolder;
    protected $saveFileName;
    protected $business_id;
    protected $extension;

    public $file;

    /**
     * 上传
     * @param $name
     * @return mixed
     */
    abstract public function put();

    public function __construct()
    {
        $this->business_id = session('Msg.business_id') ? session('Msg.business_id') : 0;
        $this->file = request()->file(Storage::$variable);
        $this->extension = strtolower(pathinfo($this->file->getInfo('name'), PATHINFO_EXTENSION));
        $option = Option::getList('image_size,file_size', 0, 'admin');
        if (!$this->file->checkExt(['txt', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'rar', 'zip', 'mp4', 'ogg', 'mov', 'mp3', 'apk', 'pdf', 'xls', 'doc', 'ppt', 'xlsx', 'docx', 'pptx'])) {
            throw new StorageException('不支持的文件类型');
        };
        if (in_array($this->extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
            $type = 'images';
            if (isset($option['image_size']) && is_numeric($option['image_size'])) {
                if (!$this->file->check(['size' => 1024 * 1024 * $option['image_size']])) {
                    throw new StorageException("图片大小受限，最大为:" . $option['image_size'] . "MB");
                };
            }
        } else {
            $type = 'files';
            if (isset($option['file_size']) && is_numeric($option['file_size'])) {
                if (!$this->file->check(['size' => 1024 * 1024 * $option['file_size']])) {
                    throw new StorageException("文件大小受限，最大为:" . $option['file_size'] . "MB");
                };
            }
        }
        $this->saveFileFolder = "/upload/{$type}/{$this->business_id}";
        $this->saveFileName = sha1($this->file->getInfo('tmp_name')) . '.' . $this->extension;
    }

    public function save()
    {
        //todo 保存到数据库
        return [
            'url' => $this->url
        ];
    }
}