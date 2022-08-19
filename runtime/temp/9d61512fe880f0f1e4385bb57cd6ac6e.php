<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:94:"/data/wwwroot/dev/chat.profittravel.com/public/../application/platform/view/setting/index.html";i:1619138110;s:85:"/data/wwwroot/dev/chat.profittravel.com/application/platform/view/layout/default.html";i:1619138110;s:82:"/data/wwwroot/dev/chat.profittravel.com/application/platform/view/common/meta.html";i:1617183426;s:84:"/data/wwwroot/dev/chat.profittravel.com/application/platform/view/common/script.html";i:1586314438;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="shortcut icon" href="/favicon.ico">
    <title><?php echo !empty($title) ? $title . '-' : null; ?><?php echo !empty($option['title']) ? $option['title'] : '来客PHP在线客服系统'; ?></title>
    <link rel="stylesheet" href="https://at.alicdn.com/t/font_353057_c9nwwwd9rt7.css?v=LK_DIY6.0.3">
    <link href="//at.alicdn.com/t/font_353057_iozwthlolt.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/css/platform/bootstrap.min.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/css/platform/common.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/css/platform/common.v2.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/css/platform/flex.css?v=LK_DIY6.0.3" rel="stylesheet">
    <script>var _upload_url = "<?php echo url('upload/file'); ?>";</script>
    <script type="text/javascript" src="/assets/js/platform/vue.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/libs/jquery/jquery.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/popper.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/bootstrap.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/common.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/common.v2.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/platform/plupload.full.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/libs/layui/layui.js?v=LK_DIY6.0.3"></script>
    <link rel="stylesheet" type="text/css" href="/assets/libs/layui/css/layui.css?v=LK_DIY6.0.3"/>
    <style>            .navbar {
            height: 200px;
            color: #fff;
            font-size: 18px;
            position: relative;
            background-image: url("/assets/images/admin/A/topbg.jpg");
        }

        .nav-top {
            position: absolute;
            top: 10px;
            left: 0;
            right: 0;
        }

        .navbar-nav .nav-link {
            color: #fff;
            font-size: 16px;
        }

        .dropdown-menu.show {
            display: inline-block;
        }

        .navbar-nav .dropdown-menu {
            position: absolute;
            background-color: #262760;
            border-radius: 10px;
        }

        .navbar-nav .dropdown-menu a {
            color: #fff;
        }

        .dropdown-item {
            height: 40px;
            line-height: 40px;
            padding: 0 0 0 20px;
            font-size: 12px;
        }

        .dropdown-item:hover {
            background-color: #1D1840;
        }

        .nav-text {
            padding-left: 36.5px;
            font-size: 22px;
        }

        .navbar .nav-menu {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            justify-content: flex-start;
            font-size: 14px;
            height: 35px;
        }

        .menu-item {
            float: left;
            margin-right: 40px;
            height: 35px;
            cursor: pointer;
        }

        .menu-item:first-of-type {
            padding-left: 16.5px;
        }

        .menu-item.active {
            color: #25c16f;
            border-bottom: 2px solid #25c16f;
        }

        .child-menu {
            background-color: #fff;
            border-radius: 8px;
            height: 65px;
            padding: 16.5px 20px;
            margin-bottom: 10px;
            display: none;
        }

        .child-menu a {
            color: #999999;
            display: inline-block;
            height: 32px;
            line-height: 32px;
            padding: 0 12px;
            text-decoration: none;
        }

        .child-menu .active {
            color: #25c16f;
            background-color: #F5FAFF;
            border-radius: 16px;
        }

        .change-password-modal .modal-dialog {
            margin-top: 15%;
            max-width: 600px;
        }

        .change-password-modal .modal-dialog .modal-content {
            width: 600px;
            border-radius: 8px;
        }

        .change-password-modal .modal-header {
            padding: 0 24px;
            height: 56px;
            line-height: 56px;
            border-bottom: 1px solid #f7f7f7;
        }

        .change-password-modal .modal-title {
            font-size: 13px;
            color: #555555;
        }

        .form-input .form-control {
            width: 300px;
            padding-left: 16px;
            border-radius: 8px;
            height: 36px;
            border: 1px solid #e5e3e9;
        }

        .change-password-modal .modal-body {
            padding: 24px 0;
        }

        .col-form-label {
            padding: 0;
            width: 160px;
            height: 36px;
            line-height: 36px;
            text-align: right;
            margin-right: 32px;
            float: left;
            font-size: 13px;
            color: #999999;
        }

        label.required::before {
            content: '';
            background-color: #ff5c5c;
            width: 6px;
            height: 6px;
            border-radius: 6px;
            top: 14px;
            left: 105px;
        }

        label.check::before {
            left: 90px;
        }

        .change-password-modal .form-group {
            margin: 0 0 24px 0;
            height: 36px;
        }

        .change-password-modal .modal-footer {
            border-top: 0;
            padding: 0;
            height: 56px;
            position: relative;
        }

        .change-password-modal .modal-footer .btn-secondary, .modal-footer .alter-password-submit {
            position: absolute;
            height: 32px;
            width: 66px;
            border-radius: 16px;
            font-size: 13px;
            color: #555555;
            top: 0;
            left: 50%;
            background-color: #f7f7f7;
            border: 1px solid #f7f7f7;
        }

        .change-password-modal .modal-footer .alter-password-submit {
            left: 35%;
            color: #fff;
            background-color: #25c16f;
            border: 1px solid #25c16f;
        }

        main.container .main-r {
            max-width: 100%;
        }

        main.container .main-r-content {
            padding: 0;
            border-color: #fff;
            border-radius: 8px;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .disabled span, .pagination li span, .pagination li a {
            margin: 0 1.5px;
            height: 32px;
            width: 32px;
            color: #555555;
            border-radius: 4px;
            text-decoration: none;
            border: 0;
        }

        .pagination li {
            border: 1px solid #f7f7f7;
        }

        .pagination li:first-of-type {
            margin-right: 11px;
        }

        .pagination li:last-of-type {
            margin-left: 11px;
        }

        .pagination .active span {
            background-color: #25c16f;
            border: 1px solid #25c16f;
            color: #fff;
        }

        .nav-menu div a {
            color: #ffffff;
        }

        .nav-menu div a.active {
            color: #25c16f;
            font-weight: 600;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="container nav-top"><a class="navbar-brand"
                                      href="<?php echo url('index/index'); ?>"><?php if (!empty($option['logo'])): ?>
                <img src="<?php echo $option['logo']; ?>" style="height: 30px;display: inline-block"><?php else: ?><img
                    src="/assets/images/platform/logo.png" style="height: 30px;display: inline-block"><?php endif; ?>
        </a>
        <ul class="navbar-nav">
            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
                                             href="http://example.com"
                                             id="dropdown01"
                                             data-toggle="dropdown"
                                             aria-haspopup="true"
                                             aria-expanded="false"><i class="iconfont icon-person"
                                                                      style="font-size: 1.3rem;line-height: 1;vertical-align: middle"></i><span><?php echo $admin['username']; ?></span></a>
                <div class="dropdown-menu" aria-labelledby="dropdown01"><a class="dropdown-item alter-password"
                                                                           href="javascript:" data-toggle="modal"
                                                                           data-target="#alterPassword">修改密码</a><a
                            class="dropdown-item" href="<?php echo url('passport/logout'); ?>">注销</a></div>
            </li>
        </ul>
    </div>
    <div class="container nav-text">管理中心</div>
    <div class="container nav-menu">
        <div style="margin: 0;padding: 0 10px;"><?php if (is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0;
                $__LIST__ = $menu;
                if (count($__LIST__) == 0) : echo ""; else: foreach ($__LIST__ as $key => $vo): $mod = ($i % 2);
                    ++$i;
                    if ($vo): ?><a class="menu-item <?php if ($route == $vo['route']): ?>active<?php endif; ?>"
                                   href="<?php echo url($vo['route']); ?>"><?php echo $vo['name']; ?></a><?php endif; endforeach; endif; else: echo "";endif; ?>
        </div>
    </div>
</nav>
<main role="main" class="container">
    <div><?php if (is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0;
            $__LIST__ = $menu;
            if (count($__LIST__) == 0) : echo ""; else: foreach ($__LIST__ as $key => $vo): $mod = ($i % 2);
                ++$i;
                if ($vo): ?>
                    <div class="child-menu"
                         data-url="<?php echo $vo['route']; ?>"><?php if (is_array($vo['children']) || $vo['children'] instanceof \think\Collection || $vo['children'] instanceof \think\Paginator): $i = 0;
                        $__LIST__ = $vo['children'];
                        if (count($__LIST__) == 0) : echo ""; else: foreach ($__LIST__ as $key => $i): $mod = ($i % 2);
                            ++$i; ?><a class="<?php if ($route == $i['route']): ?>active<?php endif; ?>"
                                       href="<?php echo url($i['route']); ?>"><span><?php echo $i['name']; ?></span>
                            </a><?php endforeach; endif; else: echo "";endif; ?>
                    </div><?php endif; endforeach; endif; else: echo "";endif; ?></div>
    <div class="main-r">
        <div class="main-r-content">
            <style>    form {
                    position: relative;
                }

                .form-input .upload a {
                    width: 80px;
                    height: 36px;
                    background-color: #2E9FFF;
                    line-height: 36px;
                    padding: 0;
                    border-color: #2E9FFF;
                    border-top-right-radius: 8px !important;
                    border-bottom-right-radius: 8px !important;
                }

                .preview {
                    display: block;
                    height: 72px;
                    width: 196px;
                    position: relative;
                    background-color: #f7f7f7;
                }

                .logo-preview {
                    display: block;
                    height: 72px;
                }

                .logo-preview img {
                    height: 100%;
                }

                .preview .upload-preview-tip {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    margin-top: 30px;
                    width: 196px;
                    text-align: center;
                    color: #bbbbbb;
                    font-size: 12px;
                    z-index: 2;
                }

                .preview img {
                    position: absolute;
                    left: 0;
                    top: 0;
                    height: 72px;
                    display: block;
                    z-index: 3;
                }

                .submit-btn {
                    padding: 0;
                    height: 32px;
                    line-height: 32px;
                    width: 66px;
                    border-radius: 16px;
                    font-size: 13px;
                    color: #fff;
                    background-color: #25c16f;
                    border: 1px solid #25c16f;
                }
            </style>
            <form method="post" return="<?php echo url('platform/setting/index'); ?>" class="auto-submit-form">
                <div class="card-body">
                    <div style="margin-bottom: 24px;"><b>基础配置</b></div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">网站名称</label>
                        <div class="form-input"><input class="form-control" value="<?php echo $option['title']; ?>"
                                                       name="title"></div>
                    </div>
                    <div class="form-group row" style="height: 120px;"><label
                                class="col-sm-3 col-form-label">LOGO图片URL</label>
                        <div class="form-input">
                            <div class="input-group mb-2"><input class="form-control"
                                                                 value="<?php echo $option['logo']; ?>"
                                                                 name="logo"><span class="input-group-btn upload"><a
                                            class="btn btn-secondary upload-btn" href="javascript:">上传图片</a></span>
                            </div><?php if (empty($option['logo'])): ?>
                                <div class="preview"><span class="upload-preview-tip">建议尺寸98&times;36</span>
                                </div><?php else: ?>
                                <div class="logo-preview"><img src="<?php echo $option['logo']; ?>">
                                </div><?php endif; ?></div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">业务域名验证文件</label>
                        <div class="form-input">
                            <div class="input-group mb-2"><input class="form-control"
                                                                 value="<?php echo $option['mp_verify']; ?>"
                                                                 name="mp_verify"><span
                                        class="input-group-btn upload"><a class="btn btn-secondary mp_verify"
                                                                          href="javascript:">上传文件</a></span></div>
                        </div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">底部版权信息</label>
                        <div class="form-input"><input class="form-control"
                                                       value="<?php echo htmlspecialchars($option['copyright']); ?>"
                                                       name="copyright"></div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">是否开启注册</label>
                        <div class="form-input"><select class="form-control" name="regist">
                                <option value="1" <?php if (isset($option['regist']) && $option['regist'] == 1) {
                                    echo 'selected';
                                } ?>>开启注册
                                </option>
                                <option value="0" <?php if (!isset($option['regist']) || $option['regist'] == 0) {
                                    echo 'selected';
                                } ?>>关闭注册
                                </option>
                            </select></div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">注册账户有效期</label>
                        <div class="form-input"><input class="form-control"
                                                       value="<?php echo(isset($option['regist_expire']) && ($option['regist_expire'] !== '') ? $option['regist_expire'] : 7); ?>"
                                                       name="regist_expire">
                            <div class="text-muted fs-sm">自动注册账户有效期，0表示永久，单位天</div>
                        </div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">注册可创建客服数</label>
                        <div class="form-input"><input class="form-control"
                                                       value="<?php echo(isset($option['regist_crnum']) && ($option['regist_crnum'] !== '') ? $option['regist_crnum'] : 1); ?>"
                                                       name="regist_crnum">
                            <div class="text-muted fs-sm">注册客服可创建的客服数量（包括注册客服）</div>
                        </div>
                    </div>
                    <div class="form-group row" style="height: 120px;"><label
                                class="col-sm-3 col-form-label">登录页背景图</label>
                        <div class="form-input">
                            <div class="input-group mb-2"><input class="form-control"
                                                                 value="<?php echo $option['passport_bg']; ?>"
                                                                 name="passport_bg"><span
                                        class="input-group-btn upload"><a class="btn btn-secondary upload-passport-btn"
                                                                          href="javascript:">上传图片</a></span></div>
                            <!--                 <div style="display: inline-block;background: #fff;border: 1px solid #e3e3e3"><img src="<?php echo $option['passport_bg']; ?>" class="passport-preview"
                         style="height: 50px;width: auto;display: inline-block" alt="请上传图片"></div> -->
                            <div class="preview"><span
                                        class="upload-preview-tip">建议尺寸1920&times;1080</span><?php if (!empty($option['passport_bg'])): ?>
                                    <img style="width: 196px" src="<?php echo $option['passport_bg']; ?>"
                                         class="passport-preview"><?php endif; ?></div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div style="margin-bottom: 6px;"><b>短信配置（阿里云）</b></div>
                        <div class="text-muted fs-sm">用于已设置手机号的账户修改密码</div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">AccessKeyId</label>
                        <div class="col-sm-6"><input class="form-control"
                                                     value="<?php echo $option['ind_sms']['aliyun']['access_key_id']; ?>"
                                                     name="ind_sms[aliyun][access_key_id]"></div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">AccessKeySecret</label>
                        <div class="col-sm-6"><input class="form-control"
                                                     value="<?php echo $option['ind_sms']['aliyun']['access_key_secret']; ?>"
                                                     name="ind_sms[aliyun][access_key_secret]"></div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">短信签名</label>
                        <div class="col-sm-6"><input class="form-control"
                                                     value="<?php echo $option['ind_sms']['aliyun']['sign']; ?>"
                                                     name="ind_sms[aliyun][sign]"></div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">验证码模板ID</label>
                        <div class="col-sm-6"><input class="form-control"
                                                     value="<?php echo $option['ind_sms']['aliyun']['tpl_id']; ?>"
                                                     name="ind_sms[aliyun][tpl_id]">
                            <div class="text-muted fs-sm">模板示例：您的验证码是${code}</div>
                        </div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3"></label>
                        <div class="form-input offset-sm-3"><a class="btn btn-primary submit-btn"
                                                               href="javascript:">保存</a></div>
                    </div>
                </div>
            </form>
            <script>    $('.upload-btn').plupload({
        url: "<?php echo url('upload/image'); ?>",
        success: function (e, res, status) {
            res = JSON.parse(res);
            if (res.code == 0) {
                $('input[name=logo]').val(res.data.url);
                $('.logo-preview').attr('src', res.data.url);
            } else {
                $.myToast({
                    content: res.msg,
                });
            }
        },
    });

    $('.mp_verify').plupload({
        url: "<?php echo url('upload/file'); ?>",
        success: function (e, res, status) {
            res = JSON.parse(res);
            if (res.code == 0) {
                $('input[name=mp_verify]').val(res.data.url);
            } else {
                $.myToast({
                    content: res.msg,
                });
            }
        },
    });

    $('.upload-passport-btn').plupload({
        url: "<?php echo url('upload/image'); ?>",
        success: function (e, res, status) {
            res = JSON.parse(res);
            if (res.code == 0) {
                $('input[name=passport_bg]').val(res.data.url);
                $('.passport-preview').attr('src', res.data.url);
            } else {
                $.myToast({
                    content: res.msg,
                });
            }
        },
    });

            </script>
        </div>
    </div>
</main><!-- /.container -->
<footer><?php if (!empty($option['copyright']) || $option['copyright'] != ''): ?>
        <div class="text-center copyright p-4"><?php echo $option['copyright']; ?></div><?php else: ?>
        <div class="text-center copyright p-4" style="color: #999;font-size: 16px">Powered by 来客PHP在线客服系统
        </div><?php endif; ?></footer><!-- Modal -->
<div class="modal fade change-password-modal" id="alterPassword" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="exampleModalLabel">修改密码</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" class="auto-submit-form alter-password-form">
                    <div class="form-group"><label class="col-form-label required">原密码</label>
                        <div class="form-input"><input type="password" name="old_password" class="form-control"
                                                       placeholder="您当前的密码"></div>
                    </div>
                    <div class="form-group"><label class="col-form-label required">新密码</label>
                        <div class="form-input"><input type="password" name="new_password"
                                                       class="form-control new-password-1"
                                                       placeholder="要设置的新密码"></div>
                    </div>
                    <div class="form-group"><label class="col-form-label required check">确认密码</label>
                        <div class="form-input"><input type="password" class="form-control new-password-2"
                                                       placeholder="再次输入新密码"></div>
                    </div>
                    <div class="form-error alert alert-danger" style="display: none">aaaaaa</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary alter-password-submit">提交</button>
            </div>
        </div>
    </div>
</div><!--无用、防止报错 start-->
<div id="file" hidden></div>
<div id="district_pick_modal" hidden></div><!--end-->
<script>            $(function(){
                $(".child-menu").find('.active').parent().show();
                let url = $(".child-menu").find('.active').parent()[0].dataset.url;     
                let first_url = $(".menu-item")[0].dataset.url;
                let sec_url = $(".menu-item")[1].dataset.url;
                let thrid_url = $(".menu-item")[2].dataset.url;
                if(url == first_url) {
                    $(".menu-item").first().addClass('active');
                }else if (url == thrid_url) {
                    $(".menu-item").last().addClass('active');
                }else if (url == sec_url) {
                    $(".menu-item:first").next().addClass('active');
                }
            });

            $(document).on("click", ".alter-password-submit", function () {
                var new_password_1 = $(".alter-password-form .new-password-1").val();
                var new_password_2 = $(".alter-password-form .new-password-2").val();
                var error = $(".alter-password-form .form-error");
                var btn = $(this);
                if (new_password_1 !== new_password_2) {
                    error.html("新密码与确认密码不一致，请重新输入").show();
                    return false;
                }
                error.hide();
                btn.btnLoading();
                $.ajax({
                    url: "<?php echo url('passport/modifyPassword'); ?>",
                    type: "post",
                    dataType: "json",
                    data: $(".alter-password-form").serialize(),
                    success: function (res) {
                        if (res.code == 0) {
                            $("#alterPassword").modal("hide");
                            $.myAlert({
                                content: res.msg,
                                confirm: function () {
                                    location.reload();
                                }
                            });
                        } else {
                            error.html(res.msg).show();
                            btn.btnReset();
                        }
                    }
                });
            });

</script>
</body>
</html>