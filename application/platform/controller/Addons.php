<?php

namespace app\platform\controller;
use think\Controller;
use think\Request;

class Addons extends Base
{
    protected $noNeedLogin = [];
    /**
     * 插件列表
     * @return \think\response\View
     */
    public function index()
    {
        //获取插件列表数据
        $addons = get_addon_list();
        $this->assign('list', $addons);
        return view();
    }

    /**
     * 插件的安装
     */
    public function install($name, $type = 0)
    {
        $class = get_addon_class($name);
        $result = (new $class)->install();
        if ($result === false) {
            if ($type) {
                $this->result('', 0, '插件安装失败', 'json');
            } else {
                $this->error('插件安装失败');
            }

        }
        //判断是否有静态资源文件
        if (is_dir(ADDON_PATH . $name . DS . "static")) {
            //将静态资源文件移动到public/static/addons
            @rename(ADDON_PATH . $name . DS . "static" . DS . $name, ROOT_PATH . 'public' . DS . 'static' . DS . 'addons' . DS . $name);
        }
        $tool = new \app\platform\model\Cache();
        $tool->setCache('on');
        $tool->setTemp('on');
        $tool->clear();
        if ($type) {
            $this->result('', 1, '插件安装成功', 'json');
        } else {
            $this->success('插件安装成功');
        }

    }

    /**
     * 插件的卸载
     */
    public function uninstall($name)
    {
        $class = get_addon_class($name);
        $result = (new $class)->uninstall();
        if ($result === false) {
            $this->error("插件卸载失败");
        }
        //还原静态资源文件
        if (is_dir(ADDON_PATH . $name . DS . "static")) {
            //将静态资源文件移动到public/static/addons
            @rename(ROOT_PATH . 'public' . DS . 'static' . DS . 'addons' . DS . $name, ADDON_PATH . $name . DS . "static" . DS . $name);
        }
        $tool = new \app\platform\model\Cache();
        $tool->setCache('on');
        $tool->setTemp('on');
        $tool->clear();
        $this->success("插件卸载成功");
    }

    //离线安装
    public function lxinstall()
    {
        // 移动到框架应用根目录/public/uploads/ 目录下
        set_time_limit(0);
        try {
            if (isset($_FILES["file"]) && ($_FILES["file"]["error"] == 0)) {
                //获取文件名
                $name = $_FILES['file']['name'];
                $type = pathinfo($name);
                $type = strtolower($type["extension"]);
                if ($type !== 'zip') {
                    $this->result('', 0, '不允许上传的插件包类型', 'json');
                }
                //获取大小
                $size = $_POST["size"];
                //获取文件类型
                $type = $_POST["type"];
                //获取文件最后修改时间
                $lastModifiedDate = $_POST["lastModifiedDate"];
                //获取分片总数
                $chunks = isset($_POST["chunks"]) ? $_POST["chunks"] : 0;
                //获取当前分片索引
                $chunk = isset($_POST["chunk"]) ? $_POST["chunk"] : '';


                // 文件保存路径
                $upload = ROOT_PATH . 'public/uploads/addons';
                // 判断文件夹是否存在，不存在则创建
                if (!is_dir($upload)) {
                    if (!mkdir($upload, '0777', true)) {
                        $this->result('', 0, '上传插件文件夹创建失败，请检查' . ROOT_PATH . 'public/uploads' . '文件夹是否有读写权限', 'json');
                    }
                }

                // 临时文件保存路径（分片）
                $tmp = ROOT_PATH . 'public/uploads/addons/tmp';
                // 判断文件夹是否存在，不存在则创建
                if (!is_dir($tmp)) {
                    if (!mkdir($tmp, '0777', true)) {

                        $this->result('', 0, '上传插件文件夹创建失败，请检查' . ROOT_PATH . 'public/uploads/addons' . '文件夹是否有读写权限', 'json');
                    }
                }


                //如果不分片的话直接保存
                if (!$chunks) {

                    $result = $this->uzip($_FILES["file"]["tmp_name"]);
                    if ($result === false) {
                        $this->result('', 0, '安装失败', 'json');
                    }
                    $result_install = $this->install($result, 1);
                    if ($result_install['code'] == 1) {
                        $this->result('', 1, '安装成功', 'json');
                    }
                    $this->result('', 0, '安装失败', 'json');
                } else {
                    // 如果分片的话先把分片存储到tmp文件夹下
                    move_uploaded_file($_FILES["file"]["tmp_name"], $tmp . "/" . $name . ".tmp" . $chunk);
                    // 判断所有分片是否都上传完毕了
                    $complete = true;
                    for ($i = 0; $i < $chunks; $i++) {
                        if (!file_exists($tmp . "/" . $name . ".tmp" . $i)) {
                            $complete = false;
                            break;
                        }
                    }
                    //如果所有分片都有的话就开始合并
                    if ($complete) {
                        $fp = fopen($upload . "/" . $name, "ab");
                        for ($i = 0; $i < $chunks; $i++) {
                            $tmp_file = $tmp . "/" . $name . ".tmp" . $i;
                            $handle = fopen($tmp_file, "rb");
                            fwrite($fp, fread($handle, filesize($tmp_file)));
                            fclose($handle);
                            unset($handle);
                            unlink($tmp_file);//合并完毕的文件就删除
                        }
                        $result = $this->uzip($upload . "/" . $name);
//                echo "--- 文件合并完毕 ---\n";
                        if ($result === false) {
                            $this->result('', 0, '安装失败', 'json');
                        }
                        $result_install = $this->install($result);
                        @unlink($upload . "/" . $name);
                        if ($result_install['code'] == 1) {
                            $this->result('', 1, '安装成功', 'json');
                        }
                        $this->result('', 0, '安装失败', 'json');
                    }
                }

            }
        } catch (Throwable $t) {
            // Executed only in PHP 7, will not match in PHP 5
            $this->result('', 0, $t->getMessage(), 'json');
        } catch (Exception $e) {
            $this->result('', 0, $e->getMessage(), 'json');
        }

    }

    //文件解压
    protected function uzip($filename)
    {
        //解压缩
        $zip = new \ZipArchive;
        //要解压的文件
        $zipfile = $filename;
        $res = $zip->open($zipfile);
        if ($res !== true) {
            return false;
        }
        //要解压到的目录
        $toDir = ADDON_PATH;
        if (!file_exists($toDir)) {
            mkdir($toDir, 755);
        }
        //获取压缩包中的文件数（含目录）
        $docnum = $zip->numFiles;
        $addonname = "";
        //遍历压缩包中的文件
        for ($i = 0; $i < $docnum; $i++) {
            $statInfo = $zip->statIndex($i);
            if ($statInfo['crc'] == 0) {
                if ($i == 0) {
                    if (is_dir($toDir . '/' . substr($statInfo['name'], 0, -1))) {
                        return false;
                    }
                    $addonname = substr($statInfo['name'], 0, -1);
                }

                //新建目录
                mkdir($toDir . '/' . substr($statInfo['name'], 0, -1));
            } else {
                //拷贝文件
                copy('zip://' . $zipfile . '#' . $statInfo['name'], $toDir . '/' . $statInfo['name']);
            }
        }
        return $addonname;
    }
}