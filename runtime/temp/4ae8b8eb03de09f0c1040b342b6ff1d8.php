<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:95:"/data/wwwroot/dev/chat.profittravel.com/public/../application/platform/view/passport/login.html";i:1610030482;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="shortcut icon" href="/favicon.ico">
    <title><?php echo !empty($title) ? $title . '-' : null; ?><?php echo !empty($option['title']) ? $option['title'] : '客服系统'; ?></title>
    <link href="/assets/css/platform/bootstrap.min.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/css/platform/common.css?v=LK_DIY6.0.3" rel="stylesheet">
    <script type="text/javascript" src="/assets/js/platform/vue.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/libs/jquery/jquery.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/popper.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/bootstrap.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/common.js?v=LK_DIY6.0.3"></script>
</head>
<body>
<style>    html, body {
        position: relative;
        min-height: 100%;
        height: 100%;
    }

    body {
        background: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url('/assets/images/admin/A/bg-logo.jpg') no-repeat fixed;
    }

    <?php if(!empty($option['passport_bg'])): ?>
    #app {
        background-image: url(<?php echo $option['passport_bg']; ?>);
    }

    .login-img {
        background-image: url(<?php echo $option['passport_bg']; ?>);
    }

    <?php endif; ?>
    .login {
        width: 100%;
        border-radius: 20px;
        height: 38px;
        font-size: 16px;
        background: linear-gradient(to right, #2E9FFF, #3E79FF);
        box-shadow: 0 4px 10px rgba(0, 123, 255, .5);
        cursor: pointer;
    }

    .username, .password, .captcha_code {
        padding: 0 20px;
        height: 36px;
        border-radius: 18px;
        background-color: #f7f5fb;
        border-color: #f7f5fb;
        margin-bottom: 20px;
    }
</style>
<div class="modal fade" id="resetPassword" data-backdrop="static">
    <div class="modal-dialog modal-sm" style="max-width: 360px">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="exampleModalLabel">忘记密码</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="send_sms_code_form"
                      action="<?php echo url('sendsms'); ?>">
                    <div class="mb-3">已绑定手机号的账户可通过手机重置密码：</div>
                    <input class="form-control mb-3" name="mobile" placeholder="手机号">
                    <div class="form-inline mb-3">
                        <div class="w-100"><input class="form-control" name="captcha_code" placeholder="图片验证码"
                                                  style="width: 150px"><img class="refresh-captcha"
                                                                            data-refresh="<?php echo url('resetcaptcha'); ?>"
                                                                            data-captcha="<?php echo url('resetcaptcha'); ?>"
                                                                            src="<?php echo url('resetcaptcha'); ?>"
                                                                            style="height: 33px;width: 80px;float: right;cursor: pointer;"
                                                                            id="reset-captcha" title="点击刷新验证码"></div>
                    </div>
                    <div class="mb-3 text-danger send-sms-code-error" style="display: none">错误</div>
                    <a href="javascript:" class="btn btn-primary send-sms-code">发送短信验证码</a></form>
                <form id="reset_password_form"
                      style="display: none"
                      action="<?php echo url('resetPassword'); ?>">
                    <div class="mb-3">短信验证码已发送到您的手机。</div>
                    <div>请选择重置密码的账户：</div>
                    <select class="form-control mb-3" name="admin_id">
                        <option v-for="item in admin_list" :value="item.id">{{item.username}}</option>
                    </select><input class="form-control mb-3" name="sms_code" placeholder="手机收到的短信验证码"><input
                            class="form-control mb-3" type="password" name="password" placeholder="新密码"><input
                            class="form-control mb-3" type="password" name="password2" placeholder="确认新密码">
                    <div class="mb-3 text-danger reset-password-error" style="display: none">错误</div>
                    <a href="javascript:" class="btn btn-primary reset-password">重置密码</a></form>
            </div>
        </div>
    </div>
</div>
<div id="app">
    <div class="opacity" style="position: static;">
        <div class="card login-card">
            <div class="card-body login-img"><?php if (!empty($option['logo'])): ?><img class="logo"
                                                                                        src="<?php echo $option['logo']; ?>"><?php else: ?>
                    <img class="logo" src="/assets/images/platform/logo.png"><?php endif; ?>
                <div class="login-form">
                    <div class="form-title" style="color: #fff;">管理员登录</div>
                    <input class="form-control username" name="username" placeholder="请输入用户名"><input
                            class="form-control password" name="password" placeholder="请输入密码" type="password">
                    <div class="form-inline mb-3">
                        <div class="w-100"><input class="form-control captcha_code" name="captcha_code"
                                                  placeholder="图片验证码" style="width: 150px"><img class="refresh-captcha"
                                                                                                src="<?php echo url('captcha'); ?>"
                                                                                                data-captcha="<?php echo url('captcha'); ?>"
                                                                                                id="login-captcha"
                                                                                                style="height: 33px;width: 80px;float: right;cursor: pointer;"
                                                                                                title="点击刷新验证码"></div>
                    </div><!--                     <div class="checkbox"><label><input type="checkbox">记住我，以后自动登录
                                            </label></div> -->
                    <button class="btn btn-block btn-primary mb-3 login">登录</button>
                    <a href="javascript:" data-toggle="modal" data-target="#resetPassword"
                       style="color: #fff;">忘记密码</a><?php if ($option['open_register'] == 1): ?><span>|</span><a
                            href="<?php echo url('platform/passport/register'); ?>">注册账号</a><?php endif; ?></div>
            </div>
        </div>
    </div>
    <div class="footer"><?php if (!empty($option['copyright']) || $option['copyright'] != ''): ?>
            <div class="footer-text copyright"><?php echo $option['copyright']; ?></div><?php else: ?>
            <div>
                <div>Powered by 来客PHP在线客服系统</div>
            </div><?php endif; ?></div>
</div>
<script src="/assets/js/ios-parallax.js?v=LK_DIY6.0.3"></script>
<script>    $('body').iosParallax({
        movementFactor: 50
    });
    var app = new Vue({
        el: '#resetPassword',
        data: {
            admin_list: [],
        },
    });

    // 验证码切换
    $("#login-captcha").on("click", function () {
        this.src = $(this).data('captcha') + '?_r=' + Math.random();
    });

    $("#reset-captcha").on("click", function () {
        this.src = $(this).data('captcha') + '?_r=' + Math.random();
    });

    $(document).on('click', '.login', function () {
        var username = $('.username').val();
        var password = $('.password').val();
        var captcha_code = $('.captcha_code').val();
        $.ajax({
            url:"<?php echo url('platform/passport/login'); ?>",
            type: 'post',
            dataType: 'json',
            data: {
                'username': username,
                'password': password,
                'captcha_code': captcha_code,
            },
            success:function (res) {
                if (res.code === 1) {
                    $.myAlert({
                        content: res.msg
                    });
                    $("#login-captcha").click();
                }else  {
                    location.href = "<?php echo url('platform/user/me'); ?>";
                }
            }
        })
    });

    $(document).on('click', '.send-sms-code', function () {
        var form = document.getElementById('send_sms_code_form');
        var mobile = form.mobile.value;
        var captcha_code = form.captcha_code.value;
        var btn = $(this);
        btn.btnLoading();
        $('.send-sms-code-error').html('').hide();
        $.ajax({
            url: form.action,
            type: 'post',
            dataType: 'json',
            data: {
                mobile: mobile,
                captcha_code: captcha_code,
            },
            complete: function () {
                btn.btnReset();
            },
            success: function (res) {
                if (res.code == 1) {
                    $('.send-sms-code-error').html(res.msg).show();
                }
                if (res.code == 0) {
                    $('#send_sms_code_form').hide();
                    $('#reset_password_form').show();
                    app.admin_list = res.data.admin_list;
                }
            },
        });
    });

    $(document).on('click', '.reset-password', function () {
        var form = document.getElementById('reset_password_form');
        var admin_id = form.admin_id.value;
        var sms_code = form.sms_code.value;
        var password = form.password.value;
        var password2 = form.password2.value;
        if (password.length < 6) {
            $('.reset-password-error').html('密码长度不能低于6位。').show();
            return false;
        }
        if (password != password2) {
            $('.reset-password-error').html('两次输入的密码不一致。').show();
            return false;
        }
        var btn = $(this);
        btn.btnLoading();
        $('.reset-password-error').html('').hide();
        $.ajax({
            url: form.action,
            type: 'post',
            dataType: 'json',
            data: {
                admin_id: admin_id,
                sms_code: sms_code,
                password: password,
            },
            complete: function () {
                btn.btnReset();
            },
            success: function (res) {
                if (res.code == 1) {
                    $('.reset-password-error').html(res.msg).show();
                }
                if (res.code == 0) {
                    $('#resetPassword').hide();
                    $.myAlert({
                        content: res.msg,
                        confirm: function () {
                            location.reload();
                        }
                    });
                }
            },
        });
    });


</script>
</body>
</html>