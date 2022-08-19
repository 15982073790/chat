<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:90:"/data/wwwroot/dev/chat.profittravel.com/public/../application/admin/view/manager/view.html";i:1620185408;s:81:"/data/wwwroot/dev/chat.profittravel.com/application/admin/view/public/header.html";i:1620746200;s:81:"/data/wwwroot/dev/chat.profittravel.com/application/admin/view/public/footer.html";i:1612957206;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head><title><?php echo $seo['business_name']; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Content-Language" content="zh-CN">
    <link rel="shortcut icon" href="/favicon.ico"/>
    <script>        YMWL_ROOT_URL = "<?php echo $baseroot; ?>";

    </script>
    <link href="/assets/libs/layer/skin/layer.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/libs/amaze/css/amazeui.min.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="/assets/libs/jquery/jquery.min.js?v=LK_DIY6.0.3"></script>
    <script src="/assets/libs/jquery/jquery.form.min.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/jquery/jquery.cookie.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/layer/layer.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/amaze/js/amazeui.min.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/layui/layui.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <link href="/assets/libs/layui/css/layui.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/libs/bootstrap/bootstrap.min.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/css/admin/common.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/css/admin/admin.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/css/admin/reload.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <script src="/assets/js/admin/functions.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <link href="/assets/css/admin/index.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/css/admin/index_me.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/css/admin/set.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <script src="/assets/js/admin/common_me.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/push/pusher.min.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/adapter.js?v=LK_DIY6.0.3"></script>
    <script type="application/javascript">        var mediaStreamTrack;
        var WEB_SOCKET_SWF_LOCATION = "/assets/libs/web_socket/WebSocketMain.swf";
        var WEB_SOCKET_DEBUG = true;
		var WEB_SOCKET_SUPPRESS_CROSS_DOMAIN_SWF_ERROR = true;
        var chat_data =Array();
        var record;
        var choose_lock = false;
        var myTitle = document.title;
        var msgreminder = <?php echo config('setting.msgreminder'); ?>;
        var config ={
          'app_key':'<?php echo $app_key; ?>',
          'whost':'<?php echo $whost; ?>',
          'value':<?php echo $value; ?>,
          'wport':<?php echo $wport; ?>        };
        function titleBlink(){
        record++;

        if(record === 3){
             record =1;
        }
         
        if(record === 1){
            document.title='【 】'+myTitle;
        }

        if(record === 2){
            document.title='【消息】'+myTitle;
        }

        if(record > 3){
              getwaitnum();
             return;
        }

          setTimeout("titleBlink()",500);//调节时间，单位毫秒。
        }

        layui.use('element', function () {
           var element = layui.element;
        });
        var wolive_connect =function () {
            pusher = new Pusher('<?php echo $app_key; ?>', {
                encrypted: <?php echo $value; ?>                , enabledTransports: ['ws']
                , wsHost: '<?php echo $whost; ?>'
                , <?php echo $port; ?>: <?php echo $wport; ?>                , authEndpoint: YMWL_ROOT_URL + '/admin/login/auth'
                ,disableStats: true
         });

            var web = "<?php echo $arr['business_id']; ?>";
            var value ="<?php echo $arr['service_id']; ?>";
            // 私人频道
            var channelme = pusher.subscribe("ud" + value);
            channelme.bind("on_notice", function (data) {
                if(data.message.type == 'change'){
                    layer.msg(data.message.msg);
                }
                getchat();
                getwait();
            });

            channelme.bind("on_chat", function (data) {
               $.cookie("cu_com",'');
               layer.msg('该访客被删除');
               getchat();
            });
            // 公共平道
            var channelall = pusher.subscribe("all" + web);
            channelall.bind("on_notice", function (data) {
                if(<?php echo $arr['groupid']; ?> == 0 || <?php echo $arr['groupid']; ?> == data.message.groupid){
                    layer.msg(data.message.msg, {offset: "20px"});
                }

                if(<?php echo $arr['groupid']; ?> != data.message.groupid){
                 
                     layer.msg('该用户向其他分组咨询！', {offset: "20px"});
                }

                  getwait();
                  getchat();
                
            });
            
            var channel =pusher.subscribe("kefu" + value);
            // 发送一个推送
            channel.bind("callbackpusher",function(data){
                $.post("<?php echo url('admin/set/callback', '', true, true); ?>",data,function(res){
                })
            });

            // 接受视频请求
            channel.bind("video",function (data) {
                getchat();
                var msg = data.message;
                var cha = data.channel;
                var cid = data.cid;
                var avatar =data.avatar;
                var username =data.username;
                layer.open({
                    type: 1,
                    title: '申请框',
                    area: ['260px', '160px'],
                    shade: 0.01,
                    fixed: true,
                    btn: ['接受', '拒绝'],
                    content: "<div style='position: absolute;left:20px;top:15px;'><img src='"+avatar+"' width='40px' height='40px' style='border-radius:40px;position:absolute;left:5px;top:5px;'><span style='width:100px;position:absolute;left:70px;top:5px;font-size:13px;overflow-x: hidden;'>"+username+"</span><div style='width:90px;height:20px;position:absolute;left:70px;top:26px;'>"+msg+"</div></div>",
                    yes: function () {
                        layer.closeAll('page');
                        var str='';
                        str+='<div class="videos">';
                        str+='<video id="localVideo" autoplay></video>';
                        str+='<video id="remoteVideo" autoplay class="hidden"></video></div>';


                        layer.open({
                          type:1
                          ,title: '视频'
                          ,shade:0
                          ,closeBtn:1
                          ,area: ['440px', '378px']
                          ,content:str
                          ,end:function(){

                           
                             mediaStreamTrack.getTracks().forEach(function (track) {
                                track.stop();
                            });
            
                          }
                      });
                        try{
                          connenctVide(cid);
                         }catch(e){
                             console.log(e);
                             return;
                         }
                        
                    },
                    btn2:function(){
                        var sid = $('#channel').text();
                        $.ajax({
                            url:YMWL_ROOT_URL+'/admin/set/refuse',
                            type:'post',
                            data:{channel:cha}
                        });

                        layer.closeAll('page');
                    }
                });
            });

            channel.bind('bind-wechat',function(data){
                layer.open({
                    content: data.message
                    ,btn: ['确定']
                    ,yes: function(index, layero){
                        location.reload();
                    }
                    ,cancel: function(){
                        return false;
                    }
                });
            });


            channel.bind('getswitch',function(data){
                layer.alert(data.message);
                getchat();
            });

            // 接受拒绝视频请求
            channel.bind("video-refuse",function (data) {
                layer.alert(data.message);
                layer.closeAll('page');
            });
            // 接受消息
            channel.bind("cu-event", function (data) {
                if("<?php echo $voice; ?>" == 'open'){
                    audioElementHovertree = document.createElement('audio');
                    audioElementHovertree.setAttribute('src', "<?php echo $voice_address; ?>");
                    audioElementHovertree.setAttribute('autoplay', 'autoplay');
                }
                var debug, portrait,showtime;
                var cdata = $.cookie("cu_com");

                if (cdata) {
                    var json = $.parseJSON(cdata);
                    debug = json.visiter_id;
                    portrait = json.avatar;
                } else {
                    debug = "";

                }

                if($.cookie("time") == ""){
                    time =data.message.timestamp;
                    $.cookie("time",time);
                    var mydate =new Date(time*1000);
                    showtime =mydate.getHours()+":"+mydate.getMinutes();
                }else{
                    time =$.cookie("time");
                    if((data.message.timestamp - time) >60){
                        var mydate =new Date(data.message.timestamp*1000);
                        showtime =mydate.getHours()+":"+mydate.getMinutes();
                    }else{
                        showtime ="";
                    }
                    $.cookie("time",data.message.timestamp);
                }
                var msg = '';
                msg += '<li class="chatmsg"><div class="showtime">' +showtime+ '</div><div style="position: absolute;left:3px;">';
                msg += '<img class="my-circle  se_pic" src="' + portrait + '" width="50px" height="50px"></div>';
                msg += "<div class='outer-left'><div class='customer'>";
                msg += "<pre>" + data.message.content + "</pre>";
                msg += "</div></div>";
                msg += "</li>";
                var str = data.message.content;
                if (data.message.visiter_id == debug) {
                    $(".conversation").append(msg);
                    getwatch(data.message.visiter_id);


                    str.replace(/<img [^>]*src=['"]([^'"]+)[^>]*>/gi, function (match, capture) {

                    var pos = capture.lastIndexOf("/");
                    var value = capture.substring(pos + 1);

                    if (value.indexOf("emo") == 0) {
                        str = data.message.content;
                    } else {
                        str = '[图片]';
                    }
                });

                str = str.replace(/<div><a[^<>]+><i>.+?<\/i>.+?<\/a><\/div>/,'[文件]');
                str = str.replace(/<a[^<>]+>.+?<\/a>/,'[超链接]');
                str =str.replace(/<img src=['"]([^'"]+)[^>]*>/gi,'[图片]');

                $("#msg" + data.message.channel).html(str);
             
                var div = document.getElementById("wrap");

                } 
                getnow(data.message);
                if(div){
                    div.scrollTop = div.scrollHeight;
                }
                $("#notices-icon").removeClass('hide');

                console.log(data);
                notify(data.message.visiter_name || '新访客', {
                    body: str,
                    icon: data.message.avatar
                }, function(notification) {
                    //可直接打开通知notification相关联的tab窗口
                    window.focus();
                    notification.close();
                    console.log('#v'+data.message.channel+' .visit_content');
                    $('#v'+data.message.channel+' .visit_content').trigger('click');
                    // $('#v'+data.message.channel+' .visit_content').trigger('click');
                });
            });


            // 通知 游客离线
            channel.bind("logout", function (data) {

                //表示访客离线
                var cdata = $.cookie("cu_com");
                var chas;
                if (cdata) {
                    var jsondata = $.parseJSON(cdata);
                    chas = jsondata.channel;
                }

                if (chas == data.message.chas) {
                    //头像变灰
                    $("#v_state").text("离线");
                }

                $("#img" + data.message.chas).addClass("icon_gray");
                getchat();

            });

            channel.bind("geton", function (data) {

                //表示访客在线
                var cdata = $.cookie("cu_com");
                var chas;
                if (cdata) {
                    var jsondata = $.parseJSON(cdata);
                    chas = jsondata.channel;
                }
                if (chas == data.message.chas) {
                    //头像变亮
                    $("#img" + data.message.chas).removeClass("icon_gray");
                    $("#v_state").text("在线");
                }

                $("#img" + data.message.chas).removeClass("icon_gray");
                getchat();

            });

            pusher.connection.bind('state_change', function(states) {
              
                if(states.current == 'unavailable' || states.current == "disconnected" || states.current == "failed" ){

                        pusher.unsubscribe("kefu" + value);
                        pusher.unsubscribe("all" + web);
                        pusher.unsubscribe("ud" + value);
            
                    if (typeof pusher.isdisconnect == 'undefined') {
                     pusher.isdisconnect = true;

                     pusher.disconnect();
                     delete pusher;
                     
                      window.setTimeout(function(){
                         wolive_connect();
                      },1000);
                    }

                 
                    $(".profile").text('离线');
                }
            });

            pusher.connection.bind('connected', function() {
                $(".profile").text('在线');
            });
        }


        function showpage(obj){

            var value =$(obj).attr("name");
            var key =$(obj).attr("id");
            layer.tips(value, '#'+key,{tips: [4, '#2F4050']});
        }




    </script>
    <script type="text/javascript" src="/assets/libs/web_socket/swfobject.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/libs/web_socket/web_socket.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/admin/online.js?v=LK_DIY6.0.3"></script>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="/assets/css/admin/ymwl_common.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
</head>
<body>
<div id="layout-west">
    <div id="layout-menus">
        <div id="layout-menus-info"><a href="javascript:;"><span class="info"
                                                                 style="color: #ffffff;font-size: 16px;vertical-align:middle;display:table-cell;position: static;left: 0"><img
                            src="<?php echo !empty($seo['logo']) ? $seo['logo'] : '/assets/images/index/workerman_logo.png'; ?>"
                            class="am-circle" style="margin: 0 10px 0 20px;"><?php echo $seo['business_name']; ?></span></a>
        </div>
        <div id="layout-menus-lists">
            <ul id="group-menus-main" class="group-menus am-list "><?php if ($part == "首页"): ?>
                    <li class="menu-item" id="title1" onmouseover="showpage(this)" name="系统首页"
                        style="background: #ffffff;color: #353535"><a href="<?php echo url('admin/index/index'); ?>"><i
                                class="layui-icon">&#xe638;</i><span style="color: #353535" class="info">系统首页</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title1" onmouseover="showpage(this)" name="系统首页"><a
                            href="<?php echo url('admin/index/index'); ?>"><i class="layui-icon">&#xe638;</i><span
                                style="color: #fff" class="info">系统首页</span></a></li><?php endif;
                if ($part == "客户咨询"): ?>
                    <li class="menu-item" id="title2" onmouseover="showpage(this)" name="客户咨询"
                        style="background:#ffffff;color: #353535"><span class="notices hide"></span><span
                            class="notices-icon hide"></span><a href="<?php echo url('admin/index/chats'); ?>"><i
                                class="layui-icon">&#xe606;</i><span style="color: #353535" class="info">客户咨询</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title2" onmouseover="showpage(this)" name="客户咨询">
                    <!-- <i id="notices-icon" class="hide"></i> --><span class="notices hide"></span><span
                            class="notices-icon hide"></span><a href="<?php echo url('admin/index/chats'); ?>"><i
                                class="layui-icon">&#xe606;</i><span style="color: #fff" class="info">客户咨询</span></a>
                    </li><?php endif;
                if ($arr['level'] != 'service'): if ($part == "历史记录"): ?>
                    <li class="menu-item" id="title7" onmouseover="showpage(this)" name="历史记录"
                        style="background: #ffffff;"><a href="<?php echo url('admin/manager/view'); ?>"><i
                                class="layui-icon">&#xe62a;</i><span style="color: #353535" class="info">历史记录</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title7" onmouseover="showpage(this)" name="历史记录"><a
                            href="<?php echo url('admin/manager/view'); ?>"><i class="layui-icon">&#xe62a;</i><span
                                style="color: #fff" class="info">历史记录</span></a></li><?php endif; endif;
                if ($part == "评价管理"): ?>
                    <li class="menu-item" id="title3" onmouseover="showpage(this)" name="评价管理"
                        style="background: #ffffff;"><a href="<?php echo url('admin/evaluate/index'); ?>"><i
                                class="layui-icon">&#xe67b;</i><span style="color: #353535" class="info">评价管理</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title3" onmouseover="showpage(this)" name="评价管理"><a
                            href="<?php echo url('admin/evaluate/index'); ?>"><i class="layui-icon">&#xe67b;</i><span
                                style="color: #fff" class="info">评价管理</span></a></li><?php endif;
                if ($part == "客户管理"): ?>
                    <li class="menu-item" id="title8" onmouseover="showpage(this)" name="客户管理"
                        style="background: #ffffff;"><a href="<?php echo url('admin/custom/index'); ?>"><i
                                class="layui-icon">&#xe770;</i><span style="color: #353535" class="info">客户管理</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title8" onmouseover="showpage(this)" name="客户管理"><a
                            href="<?php echo url('admin/custom/index'); ?>"><i class="layui-icon">&#xe770;</i><span
                                style="color: #fff" class="info">客户管理</span></a></li><?php endif;
                if ($part == "接入方法"): ?>
                    <li class="menu-item" id="title9" onmouseover="showpage(this)" name="接入方法"
                        style="background: #ffffff;"><a href="<?php echo url('admin/index/front'); ?>"><i
                                class="layui-icon">&#xe64d; </i><span style="color: #353535"
                                                                      class="info">接入方法</span></a></li><?php else: ?>
                    <li class="menu-item" id="title9" onmouseover="showpage(this)" name="接入方法"><a
                            href="<?php echo url('admin/index/front'); ?>"><i class="layui-icon">&#xe64d;</i><span
                                style="color: #fff" class="info">接入方法</span></a></li><?php endif;
                if ($part == '系统设置'): ?>
                    <li class="menu-item" id="title10" onmouseover="showpage(this)" name="系统设置"
                        style="background: #ffffff;"><a href="<?php echo url('admin/index/set'); ?>"><i
                                class="layui-icon">&#xe620;</i><span style="color: #353535" class="info">系统设置</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title10" onmouseover="showpage(this)" name="系统设置"><a
                            href="<?php echo url('admin/index/set'); ?>"><i class="layui-icon">&#xe620;</i><span
                                style="color: #fff" class="info">系统设置</span></a></li><?php endif; ?>
                <li class="menu-item <?php if ($part == '登录日志') echo 'bg-white'; ?>" onmouseover="showpage(this)"><a
                            href="<?php echo url('admin/service/loginlog'); ?>"><i
                                class="layui-icon layui-icon-log"></i><span style="color: #fff" class="info">登录日志</span></a>
                </li><?php echo hook('adminleftmenuhook', ['part' => $part]); ?></ul>
        </div>
    </div>
</div>
<script type="text/javascript">    var width =document.body.clientWidth;

    var changeall =function(){
        $("#layout-west").css("width","160px");
        $("#layout-center").css({"position":"absolute","left":"160px","width":(width-160)+"px"});
        $(".info").removeClass("hide");
        $(".west_foot2").removeClass("hide");
        $(".west_foot1").addClass("hide");
        if($(".notices")[0].textContent > 0) {
            $('.notices').removeClass("hide");
            $('.notices-icon').addClass("hide");     
        }
    }
    var changesmall =function(){
        if($(".notices")[0].textContent > 0) {
            $('.notices').removeClass("hide");
        }
    }
    setInterval(function(){
        changesmall();
    },1000);
    if (<?php echo $is_bind_wechat; ?>) {
        var index = layer.open({
            //您还未绑定微信，无法接受模板消息
            content: '当前账户未设置OPENID，无法接受模板消息，是否前去绑定'
            ,btn: ['前去绑定','忽略']
            ,yes: function(index, layero){
                $.ajax({
                    url: "/admin/index/qrcode.html",
                    dataType: 'json',
                    success: function (res) {
                        layer.close(index);
                        if(res.code){
                            layer.msg(res.msg);
                        }else{
                            layer.open({
                                title:'扫码关注,绑定公众号',
                                type: 1,
                                content: '<img style="width: 200px;height: 200px;" src="'+res.data+'"/>',
                            });
                        }
                    }
                });
                return false;
            },
            btn2: function(index, layero){
            //按钮【按钮二】的回调

            //return false 开启该代码可禁止点击该按钮关闭
            }
            ,cancel: function(){

            }
        });
    }

</script>
<div id="layout-center">
    <div id="layout-north"><a id="layout-logo" style="font-size: 1.2em;"><i
                    class="am-icon-bookmark"></i>&nbsp;<?php echo $part; ?></a>
        <ul id="layout-tools" class="am-nav am-nav-pills">
            <li>
                <div class="am-dropdown " data-am-dropdown><a class="am-dropdown-toggle" href="javascript:;"
                                                              data-am-dropdown-toggle><img id="se_avatar"
                                                                                           src="<?php echo $arr['avatar']; ?>"
                                                                                           alt="..." class="am-circle"
                                                                                           width="28px"
                                                                                           height="28px"><span
                                class="handle" id="se"><?php echo $arr['nick_name']; ?></span><span id="channel"
                                                                                                    style="display: none;"></span><span
                                id="customer" style="display: none;"></span><?php if ($arr['state'] == 'online'): ?>
                            <span class="profile">在线<i class="am-icon-caret-down"></i></span><?php else: ?><span
                                class="profile">离线<i class="am-icon-caret-down"></i></span><?php endif; ?></a>
                    <ul class="am-dropdown-content">
                        <li><a href='javascript:showinfo(<?php echo $data; ?>,<?php echo $group; ?>)'><img
                                        src="/assets/images/admin/B/person.png" alt="">个人资料</a></li>
                        <li><a href='javascript:modify(<?php echo $arr["service_id"]; ?>)'><img
                                        src="/assets/images/admin/B/change-password.png" alt="">修改密码</a>
                        </li><?php if ($arr['level'] == 'super_manager' && $referer): ?>
                            <li><a href="<?php echo url('platform/index/index'); ?>"><img
                                        src="/assets/images/admin/B/back-system.png" alt="">返回系统</a></li><?php endif; ?>
                        <li><a href="<?php echo url('admin/login/logout', ['business_id' => $arr['business_id']]); ?>"
                               class="safe-exit"><img src="/assets/images/admin/B/quit.png" alt="">安全退出
                            </a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <style>    #container {
            padding: 0;
        }

        .am-btn-default {
            background: #F5FAFF;
        }

        #gettime .am-btn {
            border: 0;
        }

        #container {
            background-color: #fff;
        }

        .am-raduis {
            width: 46px;
            height: 46px;
        }

        .fangke .f_img {
            width: 40px;
            height: 40px;
        }
    </style>
    <div id="container">
        <!--  <div id="page-header" class="am-cf"><h1 class="title"><i class="am-icon-bookmark"></i>&nbsp;历史记录</h1></div> -->
        <section class="service_list"
                 style="position: absolute;height: 100%;border-right: 2px solid #f7f7f7;overflow-y: auto;width: 20%;">
            <div style="width: 100%;height:56px;text-align: center;border-radius: 8px 0 0 0 ; border-bottom: 2px solid #f7f7f7;">
                <h4 style="font-size: 14px;font-weight: bold;position: relative;top:20px;left: -35%;color: #555555;">
                    客服列表</h4></div>
            <div class="kefu" title="<?php echo $arr['service_id']; ?>" name="<?php echo $arr['avatar']; ?>" inde
                 style="position: relative;width: 100%;height:80px;cursor: pointer;margin-left: 10px;"
                 onclick="choose(this)"><img class="am-raduis" src="<?php echo $arr['avatar']; ?>"
                                             style="position: absolute;top:15px;left: 5px;border-radius: 100%"/><span
                        style="position: absolute;left:70px;top:33%;font-size: 16px;"><?php echo $arr['nick_name']; ?></span>
            </div><?php foreach ($services as $v): ?>
                <div class="kefu" title="<?php echo $v['service_id']; ?>" name="<?php echo $v['avatar']; ?>"
                     style="position: relative;width: 100%;height:80px;cursor: pointer;margin-left: 10px;"
                     onclick="choose(this)"><img class="am-raduis" src="<?php echo $v['avatar']; ?>"
                                                 style="position: absolute;top:15px;left: 5px;border-radius: 100%"/><span
                        style="position: absolute;left:70px;top:33%;font-size: 16px;"><?php echo $v['nick_name']; ?></span>
                </div><?php endforeach; ?></section>
        <section class="c_content " style="position: absolute;left:20%;height:100%;width: 20%;">
            <div id="visiter_list" class="hide"
                 style="position: absolute;left: 0px;width: 100%;height: 100%;overflow-y: auto;">
                <header style="width: 100%;height:56px;text-align: center;"><h4
                            style="font-size: 14px;font-weight: bold;position: relative;top:20px;left: -35%;">访客列表</h4>
                </header>
            </div>
            <div id="gettime" class="hide"
                 style="position: absolute;left: 100%;width: 100%;height: 100%;border-left:1px solid #f7f7f7;border-right:1px solid #f7f7f7;">
                <header style="width: 100%;height:56px;text-align: center;"><h4
                            style="font-size: 14px;font-weight: bold;position: relative;top:20px;left: -110px;">时间段</h4>
                </header>
                <div class="vtimes" title="7"
                     style="position: relative;width: 100%;height:80px;cursor: pointer;"
                     onclick="change(this)"><i class=" am-icon-sm"
                                               style="position: absolute;top:12px;left: 5px;"></i><span
                            style="position: absolute;left:6%;top:35%;font-size: 16px;">最近7天</span></div>
                <div class="vtimes" title="1"
                     style="position: relative;width: 100%;height:80px;cursor: pointer;"
                     onclick="change(this)"><i class="am-icon-sm"
                                               style="position: absolute;top:12px;left: 5px;"></i><span
                            style="position: absolute;left:6%;top:35%;font-size: 16px;">最近1个月</span></div>
                <div class="vtimes"
                     style="position: relative;width: 100%;height:80px;cursor: pointer;"
                     onclick="changes(this)"><span style="border:0;position: absolute;left:6%;top:35%;font-size: 16px;">自定义时间</span>
                </div>
                <div id="define_time" class="hide">
                    <div class="">
                        <div class="" style="position: relative;width: 100%;height: 40px;">
                            <button type="button" class="am-btn am-btn-default am-margin-right" id="my-start">开始日期
                            </button>
                            <span id="my-startDate"></span></div>
                        <div class="" style="position: relative;width: 100%;height: 40px; ">
                            <button type="button" class="am-btn am-btn-default am-margin-right" id="my-end">结束日期
                            </button>
                            <span id="my-endDate"></span></div>
                    </div>
                    <div class="am-btn am-btn-default"
                         style="position: relative;width: 100%;height: 40px;"
                         onclick="puton()"> 提交
                    </div>
                </div>
            </div>
            <div class="no_history"><i class="no_history_icon"></i></div>
        </section>
        <section style="position: absolute;left:60%;height:100%;width: 40%">
            <div id="history_content" style="width: 100%;height: 100%;">
                <div class="h_content hide"
                     style="width: 100%;height:96%;overflow-y: auto; overflow-x: hidden;display: block; word-break: normal; border-right: 1px solid #f5f5f5;">
                    <ul id="h_show" class="conversation"></ul>
                </div>
            </div>
        </section>
    </div>
    <script type="text/javascript">    var high =document.body.clientHeight;
    $('service_list').css("height",(high-70)+"px");
    $('#visiter_list').css("height",(high -70)+"px");

    window.onresize = function(){
        var high =document.body.clientHeight;
        $('service_list').css("height",(high-70)+"px");
        $('#visiter_list').css("height",(high -70)+"px");
    }


   var getstate =function(obj){
       
       var c=obj.children[0];
 
       var state=$(obj).attr('data');
   
       if(state == 'play'){
         c.play();
         $(obj).attr('data','pause');
         $(obj).find('i').html("&#xe651;");
       
       }else if(state == 'pause'){
          c.pause();
         $(obj).attr('data','play');
          $(obj).find('i').html("&#xe652;");
       }

        c.addEventListener('ended', function () {  
         $(obj).attr('data','play');
         $(obj).find('i').html("&#xe652;");
        
       }, false);
    }

    </script>
    <script type="text/javascript" src="/assets/js/admin/view.js?v=LK_DIY6.0.3"></script>
</div>
<script type="text/javascript" src="/assets/js/video.js?v=LK_DIY6.0.3"></script>
<script>    wolive_connect();

</script>
<script type="text/javascript" src="/assets/js/80zxcom.js?v=LK_DIY6.0.3"></script>
</body>
</html>