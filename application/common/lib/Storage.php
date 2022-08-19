<?php
/**
 * @copyright ©2020 来客PHP在线客服系统
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/6/17
 * Time: 15:54
 */
namespace app\common\lib;

use app\common\lib\storage\StorageException;
use app\platform\model\Admin;
use app\platform\model\Business;
use app\platform\model\Option;
use think\App;
use think\Config;
use think\Exception;
use think\Log;

class Storage
{
    const STORAGE_TYPE_LOCAL = 1;
    const STORAGE_TYPE_ALIOSS = 2;
    const STORAGE_TYPE_TXCOS = 3;
    const STORAGE_TYPE_QINIU = 4;

    public static $variable = 'file';

    private static $play = ['Local', 'TxCos', 'AliOss', 'Qiniu'];
    /**
     * @var array
     */
    public static $instance = [];

    /**
     * @var object 操作句柄
     */
    public static $handler;

    /**
     * @var array 存储介质
     */
    public static $storage;

    /**
     * @var array 存储介质的配置
     */
    public static $config;

    /**
     * 连接Storage驱动
     */
    public static function connect($name = false)
    {
        if (false === $name) {
            $name = md5(serialize(self::$config));
        }

        if (true === $name || !isset(self::$instance[$name])) {
            $class = false === strpos(self::$storage, '\\') ?
                '\\app\\common\\lib\\storage\\driver\\' . ucwords(self::$storage) :
                self::$storage;

            // 记录初始化信息
            App::$debug && Log::record('[ STORAGE ] INIT ' . self::$storage, 'info');

            if (true === $name) {
                return new $class(self::$config);
            }

            self::$instance[$name] = new $class(self::$config);
        }

        return self::$instance[$name];
    }

    /**
     * 自动初始化Storage
     */
    public static function init(array $options = [])
    {
        $business_id = request()->param('business_id', 0);
        if (empty($business_id)) {
            $business_id = session('Msg.business_id') ? session('Msg.business_id') : 0;
        }
        if (is_null(self::$handler)) {
            if (empty($options)) {
                //获取总帐号设置的存储位置，默认为Local
                $ops = \app\admin\model\Storage::get(['admin_id' => 1, 'status' => 1]);
                if (empty($ops)) {
                    self::$storage = 'Local';
                    self::$config = [];
                } else {
                    $storage = $ops['type'];
                    self::witchStorage($storage);
                    self::$config = json_decode($ops['config'], true);
                }

                $business = Business::get($business_id);
                $admin = Admin::get([
                    'id' => $business['admin_id'],
                    'is_delete' => 0,
                ]);

                if ($business['admin_id'] != 1) {
                    $permission = json_decode($admin['permission'], true);
                    if (isset($permission['storage']) && !empty($permission['storage'])) {
                        $options = \app\admin\model\Storage::get(['admin_id' => $admin['id'], 'status' => 1]);
                        if (empty($options)) {
                            if (in_array('Local', $permission['storage'])) {
                                self::$storage = 'Local';
                                self::$config = [];
                            } else {
                                throw new StorageException('未设置存储介质~');
                            }
                        } else {
                            self::witchStorage($options['type']);
                            self::$config = json_decode($options['config'], true);
                        }
                    }
                }
            } else {
                self::$storage = $options['type'];
                self::$config = $options;
            }

            self::$handler = self::connect();
        }

        return self::$handler;
    }

    public static function witchStorage($type)
    {
        switch ($type) {
            case self::STORAGE_TYPE_LOCAL:
                self::$storage = 'Local';
                break;
            case self::STORAGE_TYPE_ALIOSS:
                self::$storage = 'AliOss';
                break;
            case self::STORAGE_TYPE_TXCOS:
                self::$storage = 'TxCos';
                break;
            case self::STORAGE_TYPE_QINIU:
                self::$storage = 'Qiniu';
                break;
            default:
                throw new \Exception('未知的存储位置: type=' . $type);
                break;
        }
        return self::$storage;
    }

    public static function put()
    {
        self::init();
        return self::$handler->put();
    }
}