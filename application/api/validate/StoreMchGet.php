<?php
/**
 * Created by 七月
 * User: 七月
 * Date: 2017/2/18
 * Time: 12:35
 */
namespace app\api\validate;

class StoreMchGet extends BaseValidate
{
    protected $rule = [
        'business_id' => 'require|isNotEmpty',
    ];

    protected $message = [
        'business_id' => '没有business_id',
    ];
}
