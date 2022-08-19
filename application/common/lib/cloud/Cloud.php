<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/3/11
 * Time: 16:25
 */
namespace app\common\lib\cloud;

use app\Common;
use app\common\lib\CurlUtils;
use think\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use think\Log;

class Cloud
{
    public $urlEncodeQueryString = true;
    private $xBaseUrl = '';
    private $xLocalAuthInfo;

    public static function getHostInfo()
    {
        $cacheKey = 'SITE_CLOUD_HOST_INFO';
        $res = Cache::get($cacheKey);
        if ($res) {
            return $res;
        }
        $common = new Common();
        $host = $common->dianqilai_access_domain();
        $curl = new CurlUtils(CloudApi::BASE_URL . CloudApi::SITE_INFO);
        $host = "";
        $domain = ["domain: $host"];
        $curl->addHeader($domain);
        $res = $curl->get();
        $res = json_decode($res, true);
        if ($res['code'] === 0) {
            Cache::set($cacheKey, $res, 600);
        } else {
            Cache::set($cacheKey, $res, 60);
        }
        return $res;
    }

    public static function getDomain()
    {
        $common = new Common();
        $host = $common->dianqilai_access_domain();
        $host = $host ? $host : request()->host();
        return $host;
    }

    public static function getVersion()
    {
        $common = new Common();
        $version = $common->dianqilai_version();
        return $version;
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed
     * @throws CloudException
     */
    public function httpGet($url, $params = [])
    {
        $url = $this->getUrl($url);
        $url = $this->appendParams($url, $params);
        try {
            $response = $this->getClient()->get($url);
            $body = $response->getBody();
        } catch (ClientException $exception) {
            $body = $exception->getResponse()->getBody();
        }
        if (!$body) {
            throw new CloudException('Cloud response is empty.');
        }
        $res = json_decode($body, true);
        if (!$res) {
            throw new CloudException('Cloud response body `' . $body . '` could not be decode.');
        }
        if ($res['code'] !== 0) {
            if ($res['code'] === -1) {
                throw new CloudNotLoginException($res['msg']);
            } else {
                throw new CloudException($res['msg']);
            }
        }
        return $res['data'];
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed
     * @throws CloudException
     */
    public function httpPost($url, $params = [], $data = [])
    {
        $url = $this->getUrl($url);
        $url = $this->appendParams($url, $params);
        try {
            $response = $this->getClient()->post($url, [
                'form_params' => $data,
            ]);
            $body = $response->getBody();
        } catch (ClientException $exception) {
            $body = $exception->getResponse()->getBody();
        }
        if (!$body) {
            throw new CloudException('Cloud response is empty.');
        }
        $res = json_decode($body, true);
        if (!$res) {
            throw new CloudException('Cloud response body `' . $body . '` could not be decode.');
        }
        if ($res['code'] !== 0) {
            throw new CloudException($res['msg']);
        }
        return $res['data'];
    }

    private function getUrl($url)
    {
        if (mb_stripos($url, 'http') === 0) {
            return $url;
        }
        $url = mb_stripos($url, '/') === 0 ? mb_substr($url, 1) : $url;
        $baseUrl = base64_decode($this->xBaseUrl);
        $baseUrl = mb_stripos($baseUrl, '/') === (mb_strlen($baseUrl) - 1) ? $baseUrl : $baseUrl . '/';
        return $baseUrl . $url;
    }

    private function appendParams($url, $params = [])
    {
        if (!is_array($params)) {
            return $url;
        }
        if (!count($params)) {
            return $url;
        }
        $url = trim($url, '?');
        $url = trim($url, '&');
        $queryString = $this->paramsToQueryString($params);
        if (mb_stripos($url, '?')) {
            return $url . '&' . $queryString;
        } else {
            return $url . '?' . $queryString;
        }
    }

    private function paramsToQueryString($params = [])
    {
        if (!is_array($params)) {
            return '';
        }
        if (!count($params)) {
            return '';
        }
        $str = '';
        foreach ($params as $k => $v) {
            if ($this->urlEncodeQueryString) {
                $v = urlencode($v);
            }
            $str .= "{$k}={$v}&";
        }
        return trim($str, '&');
    }

    public function download($url, $file)
    {
        if (!is_dir(dirname($file))) {
            if (!make_dir(dirname($file))) {
                Log::error(dirname($file) . '无法创建目录，请检查文件写入权限。');
                throw new CloudException('无法创建目录，请检查文件写入权限。');
            }
        }
        $fp = fopen($file, 'w+');
        if ($fp === false) {
            Log::error('无法保存文件，请检查文件写入权限。');
            throw new CloudException('无法保存文件，请检查文件写入权限。');
        }

        $client = new Client([
            'verify' => false,
            'stream' => true,
        ]);
        $response = $client->get($url);
        $body = $response->getBody();
        while (!$body->eof()) {
            fwrite($fp, $body->read(1024));
        }
        fclose($fp);
        return $file;
    }
}