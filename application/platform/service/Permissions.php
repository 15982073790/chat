<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/1/30
 * Time: 13:42
 */
namespace app\platform\service;


class Permissions
{

    /**
     * 获取独立版子账号权限列表
     * is_admin = false 表示子账号拥有的权限
     */
    public static function getCAdminPermission()
    {
        $menu = Menu::getMenu();
        $permissions = self::getPermissionList($menu);
        return $permissions;
    }

    public static function getPermissionList($list)
    {
        $permission = Menu::getPermission();
        $arr = [];
        foreach ($list as $k => $item) {
            if (isset($item['is_admin']) && $item['is_admin'] == false) {
                $arr[] = $item['route'];
            }

            if (isset($item['children']) && is_array($item['children'])) {
                $arr = array_merge($arr, self::getPermissionList($item['children']));
            }

            if (isset($item['sub']) && is_array($item['sub'])) {
                $arr = array_merge($arr, self::getPermissionList($item['sub']));
            }
        }

        if (!isset($permission['storage']) || empty($permission['storage'])) {
            foreach ($arr as $key => $v) {
                if ($v == 'platform/storage/index') {
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }
}