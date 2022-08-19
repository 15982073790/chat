<?php

namespace app\index\validate;

use think\Validate;


class Message extends Validate
{

    /**
     * 验证规则
     * [$rule description]
     * @var [type]
     */
    protected $rule = [
        "name" => 'require',
        "content" => "require"
    ];


    /**
     * 验证失败 信息
     * [$message description]
     * @var array
     */
    protected $message = [
        "username.require" => "请填写用户名称",
        'content.require' => '请填写留言',
    ];

}


?>