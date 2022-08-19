<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/2/18
 * Time: 9:58
 */
namespace app\admin\model;

use think\Model;

class WechatPlatform extends Model
{
    protected $field = true;

    protected $table = 'wolive_wechat_platform';

    public static function edit($post)
    {
        $model = self::get(['business_id' => $post['business_id']]);
        if (empty($model)) {
            $res = self::create($post, true);
        } else {
            $model->wx_id = $post['wx_id'];
            $model->wx_token = $post['wx_token'];
            $model->wx_aeskey = $post['wx_aeskey'];
            $model->app_id = $post['app_id'];
            $model->app_secret = $post['app_secret'];
            $model->msg_tpl = $post['msg_tpl'];
            $model->customer_tpl = $post['customer_tpl'];
            $model->visitor_tpl = $post['visitor_tpl'];
            $model->onesubtpl_id = $post['onesubtpl_id'];
            $model->onesubtpl_title = $post['onesubtpl_title'];
            $model->onesubtpl_desc = $post['onesubtpl_desc'];

            $model->desc = $post['desc'];
            $model->business_id = $post['business_id'];
            $model->isscribe = isset($post['isscribe']) ? $post['isscribe'] : 0;
            $res = $model->save();
        }
        return $res;
    }
}