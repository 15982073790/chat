<?php
use think\Db;
if (!function_exists('db')) {
    /**
     * 实例化数据库类
     * @param string $name 操作的数据表名称（不含前缀）
     * @param array|string $config 数据库配置参数
     * @param bool $force 是否强制重新连接
     * @return \think\db\Query
     */
    function db($name = '', $config = [], $force = false)
    {
        return Db::connect($config, $force)->name($name);
    }
}