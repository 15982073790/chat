<?php
/**
 * Date: 2021/4/2 0002
 * 技术支持微信: zrwx978
 */
namespace app\common\lib;

use app\extra\push\Pusher;

class SinglePusher
{
    static public $instance;//声明一个静态变量（保存在类中唯一的一个实例）

    private function __construct()
    {//声明私有构造方法为了防止外部代码使用new来创建对象。
        $sarr = parse_url(ahost);
        if ($sarr['scheme'] == 'https') {
            $state = true;
        } else {
            $state = false;
        }
        $options = array(
            'encrypted' => $state
        );
        self::$instance = new Pusher(
            app_key,
            app_secret,
            app_id,
            $options,
            ahost,
            aport
        );
    }

    static public function getinstance()
    {//声明一个getinstance()静态方法，用于检测是否有实例对象
        if (!self::$instance) new self();
        return self::$instance;
    }
}