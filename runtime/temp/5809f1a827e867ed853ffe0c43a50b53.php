<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"/data/wwwroot/dev/chat.profittravel.com/public/../application/admin/view/login/index.html";i:1618745268;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="/favicon.ico"/>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta content="telephone=no,email=no" name="format-detection"/>
    <title><?php echo(isset($business['business_name']) && ($business['business_name'] !== '') ? $business['business_name'] : "客服登陆"); ?></title>
    <script>        YMWL_ROOT_URL = '<?php echo $baseroot; ?>';

    </script><!--加载引用文件 -->
    <link href="/assets/libs/layer/admin/admin.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/libs/layer/admin/login.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/libs/layer/admin/layui.css?v=LK_DIY6.0.3" rel="stylesheet">
    <style>        .layadmin-user-login {
            background-image: url(/assets/images/admin/noise.png), url(/assets/images/admin/1.jpg);
            background-repeat: repeat, no-repeat;
            background-position: left top;
            background-size: auto, cover;
        }

    </style>
</head>
<body>
<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-header"><?php if ($business && !empty($business['logo'])): ?><img
                class="login-logo" src="<?php echo $business['logo']; ?>"><?php endif; ?>
            <h2><?php echo(isset($business['business_name']) && ($business['business_name'] !== '') ? $business['business_name'] : "客服后台"); ?></h2>
        </div>
        <form method="post" action="<?php echo $submit; ?>" class="am-form" data-submit data-am-validator>
            <div class="layadmin-user-login-body layui-form">
                <div class="layui-form-item"><label class="layadmin-user-login-icon layui-icon layui-icon-username"
                                                    for="LAY-user-login-username"></label><input type="text"
                                                                                                 name="username"
                                                                                                 id="LAY-user-login-username"
                                                                                                 lay-verify="required"
                                                                                                 placeholder="用户名"
                                                                                                 class="layui-input">
                </div>
                <div class="layui-form-item" id="login-input"><label
                            class="layadmin-user-login-icon layui-icon layui-icon-password"
                            for="password"></label><input type="password" id="password" name="password"
                                                          lay-verify="required" placeholder="密码" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <div class="layui-row">
                        <div class="layui-col-xs7"><label class="layadmin-user-login-icon layui-icon layui-icon-vercode"
                                                          for="LAY-user-login-vercode"></label><input type="text"
                                                                                                      name="captcha"
                                                                                                      lay-verify="required"
                                                                                                      placeholder="图形验证码"
                                                                                                      class="layui-input"
                                                                                                      id="LAY-user-login-vercode">
                        </div>
                        <div class="layui-col-xs5">
                            <div style="margin-left: 10px;"><img id="login-captcha"
                                                                 src="<?php echo url('captcha'); ?>?t=<?php echo mt_rand(); ?>"
                                                                 class="layadmin-user-login-codeimg" align="absmiddle"
                                                                 onclick="this.src='<?php echo url('captcha'); ?>?_r='+Math.random();"
                                                                 title="不清楚? 点击换一个"/></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="business_id"
                       value="<?php echo(isset($business_id) && ($business_id !== '') ? $business_id : ''); ?>">
                <div class="layui-form-item">
                    <button id="login-submit" class="layui-btn layui-btn-fluid" lay-submit
                            lay-filter="LAY-user-login-submit">登 入
                    </button>
                </div>
            </div><?php if ($regist == 1): ?>
                <div class="layui-form-item"><a href="<?php echo url('admin/login/sign'); ?>"
                                                style="font-size: 1.3em;color: #000;">没有账号？点击注册</a></div><?php endif; ?>
        </form>
    </div>
    <div class="layui-trans layadmin-user-login-footer">
        <p><?php if ($business && (!empty($business['copyright']) || $business['copyright'] != '')): ?><?php echo $business['copyright']; else: ?> © 2020 来客PHP在线客服系统<?php endif; ?></p>
    </div>
</div>
</body>
</html>