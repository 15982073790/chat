<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/2/14
 * Time: 15:38
 */
namespace app\platform\model;
use think\Model;
class Cache extends Model
{
    private $cache = null;
    private $temp = null;
    private $log = null;

    /**
     * @return null
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param null $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return null
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     * @param null $temp
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;
    }

    /**
     * @return null
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param null $log
     */
    public function setLog($log)
    {
        $this->log = $log;
    }

    public function setSystem($switch)
    {
        $this->_system = $switch;
    }

    public function setSystempath($path)
    {
        $this->_systempath = $path;
    }

    public function clear()
    {
        if ($this->cache) {
            $dir = RUNTIME_PATH . '/cache';
            $this->delFileUnderDir($dir);
        }
        if ($this->temp) {
            $dir = RUNTIME_PATH . '/temp';
            $this->delFileUnderDir($dir);
        }
        if ($this->log) {
            $dir = RUNTIME_PATH . '/log';
            $this->delFileUnderDir($dir);
        }
        return [
            'code' => 0,
            'msg' => '操作成功',
        ];
    }

    //循环目录下的所有文件
    private function delFileUnderDir($dirName, $delDir = false, $ignoreList = [])
    {
        if (file_exists("$dirName")) {
            if ($handle = opendir("$dirName")) {
                while (false !== ($item = readdir($handle))) {
                    if ($item != "." && $item != ".." && !in_array($item, $ignoreList)) {
                        if (is_dir("$dirName/$item")) {
                            $this->delFileUnderDir("$dirName/$item", true, $ignoreList);
                        } else {
                            unlink("$dirName/$item");
                        }
                    }
                }
                closedir($handle);
                if ($delDir) {
                    rmdir("$dirName");
                }
            }
        }
    }
}