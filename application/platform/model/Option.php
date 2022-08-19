<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/1/28
 * Time: 18:51
 */
namespace app\platform\model;

use think\Model;

class Option extends Model
{
    public static function set($title, $value, $business_id = 0, $group = '')
    {
        if (empty($title)) {
            return false;
        }
        $model = self::get([
            'title' => $title,
            'business_id' => $business_id,
            'group' => $group,
        ]);
        if (!$model) {
            $model = new Option();
            $model->title = $title;
            $model->business_id = $business_id;
            $model->group = $group;
        }
        $model->value = json_encode($value, JSON_UNESCAPED_SLASHES);
        return $model->save();
    }

    /**
     * @param $name string Name
     */
    public static function gets($title, $business_id = 0, $group = '', $default = null)
    {
        $model = self::get([
            'title' => $title,
            'business_id' => $business_id,
            'group' => $group,
        ]);
        if (!$model) {
            return $default;
        }
        return json_decode($model->value, true);
    }

    /**
     * @param $list array [ ['name'=>'nameA','value'=>'valueA','store_id'=>'store_id','group'=>'group'], ... ]
     * @return boolean
     */
    public static function setList($list, $business_id = 0, $group = '')
    {
        if (!is_array($list)) {
            return false;
        }
        foreach ($list as $item) {
            self::set($item['title'], $item['value'], (isset($item['business_id']) ? $item['business_id'] : $business_id), (isset($item['group']) ? $item['group'] : $group));
        }
        return true;
    }

    /**
     * @param $names array|string ['nameA','nameB'] or 'nameA,nameB'
     * @return array ['nameA'=>valueA,'nameB'=>valueB]
     */
    public static function getList($names, $business_id = 0, $group = '', $default = null)
    {
        if (is_string($names)) {
            $names = explode(',', $names);
        }
        if (!is_array($names)) {
            return [];
        }
        $list = [];
        foreach ($names as $name) {
            if (empty($name)) {
                continue;
            }
            $value = self::gets($name, $business_id, $group, $default);
            $list[$name] = $value;
        }
        return $list;
    }


    public static function remove($title, $business_id = 0, $group = '')
    {
        $model = Option::get([
            'title' => $title,
            'business_id' => $business_id,
            'group' => $group,
        ]);
        if ($model) {
            return $model->delete();
        }
        return false;
    }
}