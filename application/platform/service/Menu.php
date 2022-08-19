<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/1/28
 * Time: 17:11
 */
namespace app\platform\service;

use app\platform\model\Admin;
use think\Session;

class Menu
{
    private static $permission;

    public static $route = [
        'platform/user/me',
        'platform/app/index',
        'platform/app/edit',
        'platform/app/recycle',
        'platform/cache/index',
        'platform/storage/index',
    ];

    public static function getMenus()
    {
        self::getPermission();
        $menu = self::getMenu();
        $menuList = self::resetList($menu, self::$route);
        $menuList = self::delete($menuList);
        return $menuList;
    }

    public static function getPermission()
    {
        $admin = Admin::get(['id' => self::getUid()]);
        self::$permission = !empty($admin['permission']) ? json_decode($admin['permission'], true) : [];
        return self::$permission;
    }

    private static function getUid()
    {
        return Auth::instance()->getUser()->id;
    }

    public static function resetList($list, $route)
    {
        foreach ($list as $k => $item) {
            if (self::getUid() == 1) {
                $list[$k]['show'] = true;
            } else {
                if (in_array($item['route'], $route)) {
                    $list[$k]['show'] = true;
                } else {
                    $list[$k]['show'] = false;
                }
            }
            if (isset($item['children']) && is_array($item['children'])) {
                $list[$k]['children'] = self::resetList($item['children'], $route);
                foreach ($list[$k]['children'] as $i) {
                    if ($i['show']) {
                        $list[$k]['route'] = $i['route'];
                        $list[$k]['show'] = true;
                        break;
                    }
                }
            }
        }

        return $list;
    }


    public static function delete($menuList)
    {
        foreach ($menuList as $k1 => $item) {
            if (isset($item['children'])) {
                $menuList[$k1]['children'] = self::delete($item['children']);
            }

            if ($item['show'] == false) {
                unset($menuList[$k1]);
            }

            if (self::getUid() != 1 && $item['route'] == 'platform/storage/index') {
                if (!isset(self::$permission['storage']) || empty(self::$permission['storage'])) {
                    unset($menuList[$k1]);
                }
            }
        }

        return $menuList;
    }


    public static function getMenu()
    {
        return [
            [
                'name' => '账户管理',
                'route' => '',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'name' => '我的账户',
                        'route' => 'platform/user/me',
                        'icon' => 'icon-person',
                        'is_admin' => false,
                    ],
                    [
                        'name' => '账户列表',
                        'route' => 'platform/user/index',
                        'icon' => 'icon-liebiao',
                        'is_admin' => true,
                    ],
                    [
                        'name' => '新增子账户',
                        'route' => 'platform/user/edit',
                        'icon' => 'icon-add1',
                        'is_admin' => true,
                    ],
                    [
                        'name' => '登录日志',
                        'route' => 'platform/admin/loginlog',
                        'icon' => 'icon-add1',
                        'is_admin' => true,
                    ]
                ]
            ],
            [
                'name' => '客服系统',
                'route' => '',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'name' => '我的应用',
                        'route' => 'platform/app/index',
                        'icon' => 'icon-shanghu',
                        'is_admin' => false,
                        'sub' => [
                            [
                                'name' => '添加应用',
                                'route' => 'platform/app/edit',
                                'is_admin' => false,
                            ],
                            [
                                'name' => '进入应用',
                                'route' => 'platform/app/entry',
                                'is_admin' => false,
                            ],
                            [
                                'name' => '删除应用',
                                'route' => 'platform/app/delete',
                                'is_admin' => false,
                            ],
                            [
                                'name' => '应用回收站',
                                'route' => 'platform/app/recycle',
                                'is_admin' => false,
                            ],
                            [
                                'name' => '设置回收站',
                                'route' => 'platform/app/setrecycle',
                                'is_admin' => false,
                            ],
                            [
                                'name' => '应用禁用',
                                'route' => 'platform/app/disabled',
                                'is_admin' => false,
                            ],
                            [
                                'name' => '修改客服系统管理员密码',
                                'route' => 'platform/app/modifypassword',
                                'is_admin' => false
                            ]
                        ]
                    ],
                    [
                        'name' => '回收站',
                        'route' => 'platform/app/recycle',
                        'icon' => 'icon-huishouzhan',
                        'is_admin' => false,
                    ],
                    [
                        'name' => '子账户应用',
                        'route' => 'platform/app/subapp',
                        'icon' => 'icon-xiaochengxu4',
                        'is_admin' => true,
                    ],
                ]
            ],
            [
                'name' => '设置',
                'route' => '',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'name' => '系统设置',
                        'route' => 'platform/setting/index',
                        'icon' => 'icon-settings',
                        'is_admin' => true,
                    ],
                    [
                        'name' => '清理缓存',
                        'route' => 'platform/cache/index',
                        'icon' => 'icon-qinglihuancun',
                        'is_admin' => false,
                    ],
                    [
                        'name' => '上传设置',
                        'route' => 'platform/storage/index',
                        'icon' => 'icon-settings',
                        'is_admin' => false,
                    ],
                    [
                        'name' => '更新',
                        'route' => 'platform/update/index',
                        'icon' => 'icon-settings',
                        'is_admin' => true,
                    ],
                ]
            ],
            [
                'name' => '插件管理',
                'route' => 'platform/addons/index',
                'icon' => '',
                'children' => [

                ]
            ]
        ];
    }
}