<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/3/19
 * Time: 9:35
 */

namespace app\common\lib\cloud;

use Alchemy\Zippy\Zippy;
use app\common\lib\CurlUtils;
use think\Db;
use think\Exception;
use think\Log;

class CloudUpdate extends Cloud
{
    protected $src = null;
    protected $version = LK_VERSION;
    protected $url = null;

    public function __construct()
    {
        $this->url = CloudApi::BASE_URL . CloudApi::SITE_UPDATE;
    }

    public function info()
    {
        $curl = new CurlUtils($this->url);
        $host = Cloud::getDomain();
        $version = Cloud::getVersion();
        $data = ["domain: $host", "version:$version"];
        $curl->addHeader($data);
        $res = $curl->get();
        $res = json_decode($res, true);
        $res['data']['local_version'] = $this->version;
        if (isset($res['data']['next_version'])) {
            $this->src = $res['data']['next_version']['src_file'];
        } else {
            $this->src = '';
        }

        return $res;
    }

    public function update()
    {
        $versionData = self::info()['data'];
        if (!isset($versionData['next_version']) || !$versionData['next_version']) {
            throw new CloudException('已无新版本。');
        }
        $version = $versionData['next_version']['version'];
        $src = $versionData['next_version']['src_file'];
        $tempFile = RUNTIME_PATH . '/update-package/' . $version . '/src.zip';
        Log::info("===============Update Begin===============");
        Log::info("Update Version:" . $version);
        $this->download($src, $tempFile);
        $zippy = Zippy::load();
        $archive = $zippy->open($tempFile);
        $archive->extract(dirname(ROOT_PATH));
        unset($archive);
        $result = null;
        if (file_exists(ROOT_PATH . '/update.sql')) {
            ob_start();
            $sql = file_get_contents(ROOT_PATH . '/update.sql');
            $sql = str_replace("\r", "\n", $sql);
            $array = explode(";\n", trim($sql));
            foreach ($array as $item) {
                self::uploadsql(trim($item));
            }
            $result = ob_get_contents();
            ob_clean();
            unlink(ROOT_PATH . '/update.sql');
        }
        Log::info("===============Update End===============");
        return $result;
    }

    public function uploadsql($sql)
    {
        try {
            Db::execute($sql);
        } catch (Exception $exception) {
            Log::error(">>>update " . $sql . "<>" . $exception->getMessage());
        }
    }


}